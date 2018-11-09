<?php

namespace App;

use App\Contracts\ResumeTokenInterface;
use App\Option;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\File;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Facades\Storage;
use PDF;
use SnappyImage;

/**
 * @package Resume Builder
 * @author  Abhishek Prakash <prakashabhishek6262@gmail.com>
 */
class Resume extends Model implements ResumeTokenInterface
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'data',
        'template',
        'title'
    ];

    /**
     * Defines the relationship between the user and their resumes.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function author() {
        return $this->belongsTo(User::class);
    }

    /**
     * Defines the relationship between the resume and its token.
     * .
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function token() {
        return $this->hasOne(ResumeToken::class);
    }

    /**
     * Deletes a resume template by the supplied name.
     * 
     * @param  string $template
     * 
     * @return bool
     */
    public static function deleteTemplate(string $template) : bool {
        $path = resource_path("views/resumes/" . $template);

        if ( File::deleteDirectory($path) ) {
            return true;
        }

        return false;
    }

    /**
     * Returns the contact information from the supplied data.
     * 
     * @param  array $data
     * 
     * @return array
     */
    public static function extractContactInfo($data) {
        $contact_info = array_filter($data, function ($temp) {
            return $temp->type === 'contact-information';
        });

        return count($contact_info) > 0 ? $contact_info[0] : $contact_info;
    }

    /**
     * Generates a preview for the resume.
     *
     * @param  array $props
     *
     * @return string
     */
    public function generatePreview($props = []) {
        $author   = array_key_exists('author', $props) ? $props['author'] : $this->author;
        $data     = array_key_exists('data', $props) ? $props['data'] : json_decode($this->data);
        $template = array_key_exists('template', $props) ? $props['template'] : $this->template;
        $title    = array_key_exists('title', $props) ? $props['title'] : $this->title;

        // Extract out the contact information from the data so it can be
        // reused easily whenever required in the future by the templates.
        $contact_info = self::extractContactInfo($data);

        // Finally, we can generate a preview of the resume and store it
        // in the asset to return back the image url to the client
        // application to reuse it as they want.
        $adapter_path = Storage::disk('public')->getAdapter()->getPathPrefix();
        $file_path    = 'previews/' . sha1(time()) . '.png';

        SnappyImage::loadView('resumes.' . $template . '.index', [
            'author'       => $author,
            'contact_info' => $contact_info,
            'data'         => $data,
            'template'     => $template,
            'title'        => $title,
        ])
            ->setOption("width", 750)
            ->setOption("height", 1035)
            ->setOption("crop-w", 750)
            ->setOption("crop-h", 1035)
            ->setOption("disable-smart-width", true)
            ->save($adapter_path . $file_path);

        return Storage::disk('public')->url($file_path);
    }

    /**
     * Returns an instance of PDF generated for the resume.
     * 
     * @param  array $props
     * 
     * @return PDF
     */
    public function generatePDF($props = []) {
        $author   = array_key_exists('author', $props) ? $props['author'] : $this->author;
        $data     = array_key_exists('data', $props) ? $props['data'] : json_decode($this->data);
        $template = array_key_exists('template', $props) ? $props['template'] : $this->template;
        $title    = array_key_exists('title', $props) ? $props['title'] : $this->title;

        // Extract out the contact information from the data so it can be
        // reused easily whenever required in the future by the templates.
        $contact_info = self::extractContactInfo($data);

        return PDF::loadView('resumes.' . $template . '.index', [
            'author'       => $author,
            'contact_info' => $contact_info,
            'data'         => $data,
            'template'     => $template,
            'title'        => $title,
        ])
            ->setPaper('a4');
    }

    /**
     * Generates a token that gets stored into the user's browser so they enjoy
     * the same privilege as of the owner of the resume even when they're not
     * authenticated in the application.
     *
     * @return \Illuminate\Database\Eloquent\Model
     */
    public function generateToken() {
        $key = bcrypt(Carbon::now());

        $tokens = Cookie::get('resumes', null);
        $tokens = ! empty($tokens) ? json_decode($tokens) : [];

        array_push($tokens, [
            'key'       => $key,
            'resume_id' => $this->id
        ]);

        Cookie::queue('resumes', json_encode($tokens), "2628000");
        return $this->token()->create([
            'key' => $key
        ]);
    }

    /**
     * Returns a list of templates that should be prohibited from being
     * returned the client.
     * 
     * @return array
     */
    public static function getIgnoredTemplates() : array {
        $templates = Option::where('name', 'ignore_templates')->firstOrFail(['value']);
        return unserialize($templates['value']);
    }

    /**
     * Returns list of all templates for the resume.
     * 
     * @return array
     */
    public static function getTemplates() : array {
        $templates_path = resource_path("views/resumes/");
        $thumbnails_dir = "thumbnails/";

        $templates = array_values(
                        array_filter(
                            glob($templates_path . "*"), "is_dir"
                        )
                    );

        $templates = array_map(function ($template) use ($templates_path, $thumbnails_dir) {
            $name = basename($template);
            $thumbnail_path = $thumbnails_dir . $name . ".jpg";

            // If we don't find the thumbnail in the thumbnails directory
            // already, then we will assume that it's a new template and
            // we'll proceed to look for the thumbnail in the template's
            // own directory.
            if (! Storage::exists($thumbnail_path)) {
                $temp_thumbail_path = $templates_path . $name . "/thumbnail.jpg";

                if (file_exists($temp_thumbail_path)) {
                    $file = new File($temp_thumbail_path);
                    Storage::disk('public')->putFileAs($thumbnails_dir, $file, $name . '.jpg');
                }
            }

            $thumbnail = Storage::url($thumbnail_path);

            return [
                'name'    => $name,
                'preview' => $thumbnail
            ];
        }, $templates);

        return $templates;
    }

    /**
     * Returns a list of unignored templates to returned to the client.
     * 
     * @return array
     */
    public static function getUnignoredTemplates() : array {
        $ignore_templates = self::getIgnoredTemplates();
        $templates        = self::getTemplates();

        return array_values(
                array_where($templates, function ($template) use ($ignore_templates) {
                    return ! in_array($template['name'], $ignore_templates);
                })
            );
    }

    /**
     * Hides the resume template for the client.
     * 
     * @param  $template
     * 
     * @return bool
     */
    public static function ignoreTemplate(string $template) : bool {
        $templates = Option::where('name', 'ignore_templates')->firstOrFail(['id', 'value']);
        $values    = unserialize($templates['value']);

        if (array_search($template, $values) !== false) {
            return false;
        }

        array_push($values, $template);

        $templates->value = serialize($values);
        $templates->save();

        return true;
    }

    /**
     * Unhides the resume template for the client.
     * 
     * @param  $template
     * 
     * @return bool
     */
    public static function unignoreTemplate(string $template) : bool {
        $templates = Option::where('name', 'ignore_templates')->firstOrFail(['id', 'value']);
        $values    = unserialize($templates['value']);

        if (($key = array_search($template, $values)) === false) {
            return false;
        }

        unset($values[$key]);

        $templates->value = serialize($values);
        $templates->save();

        return true;
    }

    /**
     * Determines whether the resume contains a token and is valid for the
     * user's browser.
     *
     * @return bool
     */
    public function validateToken(): bool {
        if (! $this->token()->exists()) {
            return false;
        }

        $tokens = Cookie::get('resumes', null);
        $tokens = ! empty($tokens) ? json_decode($tokens) : [];

        $token = array_where($tokens, function ($token) {
            return $token->resume_id === $this->id && $token->key === $this->token->key;
        });

        if (count($token) > 0) {
            return true;
        }

        return false;
    }
}

<footer id="footer" class="site-footer">
    <div class="py-5 text-muted text-center text-small">
        <p class="mb-1">© {{ (new \Carbon\Carbon())->year . ' - ' . ((new \Carbon\Carbon())->year + 1) . ' ' . config('app.name', 'Resume Builder') }}. Developed by <a href="https://github.com/abhishek6262" target="_blank">Abhishek Prakash</a>.</p>

        <ul class="list-inline">
            <li class="list-inline-item"><a href="#">Privacy</a></li>
            <li class="list-inline-item"><a href="#">Terms</a></li>
            <li class="list-inline-item"><a href="#">Support</a></li>
        </ul>
    </div>
</footer>
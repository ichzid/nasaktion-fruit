</div><!-- end p-6 -->
        </main>
    </div>

    <script>
        // Auto-hide flash messages
        setTimeout(() => {
            document.querySelectorAll('.bg-green-50, .bg-red-50').forEach(el => {
                el.style.transition = 'opacity 0.5s';
                el.style.opacity = '0';
                setTimeout(() => el.remove(), 500);
            });
        }, 4000);
    </script>
</body>
</html>
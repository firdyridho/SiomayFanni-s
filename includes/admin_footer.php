<?php
// File: includes/admin_footer.php
?>
            </div> <!-- .admin-content -->
        </div> <!-- .admin-main -->
    </div> <!-- .admin-layout -->

    <script>
        // Mobile sidebar toggle
        document.addEventListener('DOMContentLoaded', function() {
            const mobileMenuBtn = document.querySelector('.mobile-menu-btn');
            const sidebar = document.querySelector('.admin-sidebar');
            
            if (mobileMenuBtn) {
                mobileMenuBtn.addEventListener('click', function() {
                    sidebar.classList.toggle('active');
                });
            }
            
            // Close sidebar when clicking outside on mobile
            document.addEventListener('click', function(event) {
                if (window.innerWidth <= 768) {
                    if (!sidebar.contains(event.target) && !mobileMenuBtn.contains(event.target)) {
                        sidebar.classList.remove('active');
                    }
                }
            });
            
            // Add active class to current page menu item
            const currentPage = window.location.pathname.split('/').pop();
            const menuItems = document.querySelectorAll('.menu-item a');
            
            menuItems.forEach(item => {
                const href = item.getAttribute('href');
                if (href === currentPage) {
                    item.parentElement.classList.add('active');
                }
            });
        });
        
document.addEventListener('DOMContentLoaded', function() {
    const mobileMenuBtn = document.getElementById('mobileMenuBtn');
    const sidebar = document.querySelector('.admin-sidebar');
    const sidebarToggle = document.querySelector('.sidebar-toggle');
    const moreMenuTrigger = document.querySelector('.more-menu-trigger');
    const moreMenuDropdown = document.querySelector('.more-menu-dropdown');
    const mobileOverlay = document.createElement('div');
    
    // Create mobile overlay
    mobileOverlay.className = 'mobile-overlay';
    document.body.appendChild(mobileOverlay);
    
    // Toggle sidebar dengan hamburger button di topbar
    if (mobileMenuBtn) {
        mobileMenuBtn.addEventListener('click', function() {
            sidebar.classList.toggle('mobile-active');
            mobileOverlay.classList.toggle('active');
        });
    }
    
    // Toggle sidebar dengan menu button di bottom nav
    if (sidebarToggle) {
        sidebarToggle.addEventListener('click', function(e) {
            e.preventDefault();
            sidebar.classList.toggle('mobile-active');
            mobileOverlay.classList.toggle('active');
        });
    }
    
    // Close sidebar ketika klik overlay
    mobileOverlay.addEventListener('click', function() {
        sidebar.classList.remove('mobile-active');
        mobileOverlay.classList.remove('active');
        moreMenuDropdown.style.opacity = '0';
        moreMenuDropdown.style.visibility = 'hidden';
    });
    
    // More menu functionality
    if (moreMenuTrigger) {
        moreMenuTrigger.addEventListener('click', function(e) {
            e.preventDefault();
            const isVisible = moreMenuDropdown.style.opacity === '1';
            
            if (isVisible) {
                moreMenuDropdown.style.opacity = '0';
                moreMenuDropdown.style.visibility = 'hidden';
            } else {
                // Close semua dropdown lainnya
                document.querySelectorAll('.more-menu-dropdown').forEach(dropdown => {
                    dropdown.style.opacity = '0';
                    dropdown.style.visibility = 'hidden';
                });
                
                moreMenuDropdown.style.opacity = '1';
                moreMenuDropdown.style.visibility = 'visible';
            }
        });
    }
    
    // Close more menu ketika klik di luar
    document.addEventListener('click', function(e) {
        if (!e.target.closest('.center-menu')) {
            if (moreMenuDropdown) {
                moreMenuDropdown.style.opacity = '0';
                moreMenuDropdown.style.visibility = 'hidden';
            }
        }
    });
    
    // Add active class to current page
    const currentPage = window.location.pathname.split('/').pop();
    const menuItems = document.querySelectorAll('.menu-item a, .bottom-nav-item a:not(.more-menu-trigger)');
    
    menuItems.forEach(item => {
        const href = item.getAttribute('href');
        if (href === currentPage) {
            item.classList.add('active');
            item.closest('.menu-item')?.classList.add('active');
        }
    });
    
    // Handle window resize
    function handleResize() {
        if (window.innerWidth > 768) {
            // Reset mobile states on desktop
            sidebar.classList.remove('mobile-active');
            mobileOverlay.classList.remove('active');
            if (moreMenuDropdown) {
                moreMenuDropdown.style.opacity = '0';
                moreMenuDropdown.style.visibility = 'hidden';
            }
        }
    }
    
    // Initial check
    handleResize();
    window.addEventListener('resize', handleResize);
});
    </script>
</body>
</html>
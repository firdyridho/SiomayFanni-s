<?php
// File: includes/admin_footer.php
?>
            </div> <!-- .admin-content -->
        </div> <!-- .admin-main -->
    </div> <!-- .admin-layout -->

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const mobileMenuBtn = document.getElementById('mobileMenuBtn');
            const sidebar = document.querySelector('.admin-sidebar');
            
            // Mobile sidebar toggle (untuk tablet)
            if (mobileMenuBtn && sidebar) {
                mobileMenuBtn.addEventListener('click', function() {
                    sidebar.style.display = sidebar.style.display === 'flex' ? 'none' : 'flex';
                });
            }
            
            // Add active class to current page menu item
            const currentPage = window.location.pathname.split('/').pop();
            const menuItems = document.querySelectorAll('.menu-item a');
            const bottomNavItems = document.querySelectorAll('.bottom-nav-item a');
            
            // Update desktop menu
            menuItems.forEach(item => {
                const href = item.getAttribute('href');
                if (href === currentPage) {
                    item.parentElement.classList.add('active');
                }
            });
            
            // Update mobile bottom nav
            bottomNavItems.forEach(item => {
                const href = item.getAttribute('href');
                if (href === currentPage) {
                    item.classList.add('active');
                }
            });
            
            // Handle window resize
            function handleResize() {
                if (window.innerWidth > 768) {
                    // Show sidebar on desktop
                    if (sidebar) sidebar.style.display = 'flex';
                } else {
                    // Hide sidebar on mobile
                    if (sidebar) sidebar.style.display = 'none';
                }
            }
            
            // Initial check
            handleResize();
            
            // Listen for resize events
            window.addEventListener('resize', handleResize);
            
            // Close sidebar when clicking outside on mobile
            document.addEventListener('click', function(event) {
                if (window.innerWidth <= 768 && sidebar) {
                    if (!sidebar.contains(event.target) && !mobileMenuBtn.contains(event.target)) {
                        sidebar.style.display = 'none';
                    }
                }
            });
        });

        // Di footer.php atau file JavaScript terpisah
document.addEventListener('DOMContentLoaded', function() {
    // Customer Bottom Nav Functionality
    const customerQuickActions = document.querySelector('.customer-quick-actions');
    const closeCustomerActions = document.querySelector('.customer-quick-actions .close-actions');
    const customerCenterMenu = document.querySelector('.customer-bottom-nav .center-menu');
    
    // Quick Actions untuk customer dengan long press
    if (customerCenterMenu) {
        let pressTimer;
        
        customerCenterMenu.addEventListener('mousedown', function() {
            pressTimer = window.setTimeout(function() {
                customerQuickActions.classList.add('active');
            }, 500);
        });
        
        customerCenterMenu.addEventListener('touchstart', function() {
            pressTimer = window.setTimeout(function() {
                customerQuickActions.classList.add('active');
            }, 500);
        });
        
        customerCenterMenu.addEventListener('mouseup', function() {
            clearTimeout(pressTimer);
        });
        
        customerCenterMenu.addEventListener('mouseleave', function() {
            clearTimeout(pressTimer);
        });
        
        customerCenterMenu.addEventListener('touchend', function() {
            clearTimeout(pressTimer);
        });
    }
    
    // Close quick actions
    if (closeCustomerActions) {
        closeCustomerActions.addEventListener('click', function() {
            customerQuickActions.classList.remove('active');
        });
    }
    
    // Close quick actions ketika klik di luar
    customerQuickActions.addEventListener('click', function(e) {
        if (e.target === customerQuickActions) {
            customerQuickActions.classList.remove('active');
        }
    });
    
    // Mobile menu button functionality
    const mobileMenuBtn = document.querySelector('.mobile-menu-btn');
    const customerNavLinks = document.querySelector('.customer-header .nav-links');
    
    if (mobileMenuBtn && customerNavLinks) {
        mobileMenuBtn.addEventListener('click', function() {
            customerNavLinks.style.display = customerNavLinks.style.display === 'flex' ? 'none' : 'flex';
        });
        
        // Hide menu ketika resize ke desktop
        window.addEventListener('resize', function() {
            if (window.innerWidth > 768) {
                customerNavLinks.style.display = 'flex';
            } else {
                customerNavLinks.style.display = 'none';
            }
        });
    }
    
    // Add active class to current page untuk customer
    const currentPage = window.location.pathname.split('/').pop();
    const customerMenuItems = document.querySelectorAll('.customer-bottom-nav .bottom-nav-item a');
    
    customerMenuItems.forEach(item => {
        const href = item.getAttribute('href');
        if (href === currentPage) {
            item.classList.add('active');
        }
    });
});
    </script>
</body>
</html>
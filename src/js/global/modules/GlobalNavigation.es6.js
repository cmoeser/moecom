/* jshint esnext: true */

// When mobile menu is open, a custom event on $(window) is triggered
// 'mobileMenuOpen' / 'mobileMenuOpen'
// Universal subnav listens for event to apply a max width fix to scroll container

class GlobalNavigation {
	constructor($, Utils) {
        let _this = this;
        let utils = new Utils();

        let selectors = {
            skipMain: 'skip-main', // skip-main link
            siteWrap: 'outer',
            mainNavSection: 'main-nav-section',
            navItem: 'nav-item',
            navItemA: 'nav-item > a',
            subNav: 'sub-nav',
            closeSubNav: 'close-sub-nav',
            menuToggle: 'menu-toggle',
            locationSignInA: 'location-signin a',
            navSearchA: 'nav-search a',
            mobileSearch: 'mobile-search',
            topBar: 'top-bar',
            logo: 'mck-logo-icon'
        };

        this.selectors = selectors; // make available for testing

        let dynamicClasses = {
            mobileTransitionsActive: '_mobile-transitions', // Mobile: activates transitions
            displayBlock: '_display-block', // Mobile: content is display:none to prevent tab-through when off screen, class forces diplay:block
            hoverOpen: '_hover-open',
            menuOpen: '_menu-open',
            subNavActive: '_sub-nav-active', // Mobile: the individual sub-nav that is active (visible)
            subNavOpen: '_sub-nav-open', // Mobile: this slides the entire level 1 menu over
            fixed: '_fixed', // Mobile: prevent scrolling on main body content when nav is open
            tapToClose: '_tap-to-close' // Mobile: sitewrap class that allows listener for tap to close
        };

        let constants = {
            mobileTransitionSpeed: 350 //300 ms
        };

        let $globalHeader = $('.global-header'),
            $body = $('body'),
            $siteWrap = $('.'+selectors.siteWrap),
            $skipMain = $globalHeader.find('.' + selectors.skipMain),
            $mainNavSection = $globalHeader.find('.' + selectors.mainNavSection),
            $navItems = $globalHeader.find('.' + selectors.navItem),
            $navItemAs = $globalHeader.find('.' + selectors.navItemA),
            $subNavs = $globalHeader.find('.' + selectors.subNav),
            $closeSubNav = $globalHeader.find('.' + selectors.closeSubNav),
            $menuToggle = $globalHeader.find('.' + selectors.menuToggle),
            $locationSignInA = $globalHeader.find('.' + selectors.locationSignInA),
            $navSearchA = $globalHeader.find('.' + selectors.navSearchA),
            $mobileSearch = $globalHeader.find('.' + selectors.mobileSearch),
            $logos = $globalHeader.find('.' + selectors.logo),
            $topBar = $globalHeader.find('.' + selectors.topBar),
            $topBarLogo = $topBar.find('.' + selectors.logo),
            $window = $(window);

        // Test if webkit-overflow-scrolling is supported
        let webkitOverflowScrollingSupport = utils.webkitOverflowScrollingSupport();

        // Delayed add and remove of mobile transitions so nav does not flash when window resizes from tablet to mobile
        let activateMobileTransitions = function(delay) {
            if (!$globalHeader.hasClass(dynamicClasses.mobileTransitionsActive)){ // prevent multiple triggering
                window.setTimeout(function(){
                    $globalHeader.addClass(dynamicClasses.mobileTransitionsActive);
                },(delay || 500));
            }
        };

        let deactivateMobileTransitions = function() {
            $globalHeader.removeClass(dynamicClasses.mobileTransitionsActive);
        };

        this.activateMobileTransitions = function() { // for init
            if (utils.getViewportSize() === 'small') {
                activateMobileTransitions(0);
            }
        };

        // returns true if mobile Menu is open
        this.isMobileMenuOpen = function() {
            if ($globalHeader.hasClass(dynamicClasses.menuOpen)){
                return true;
            }
            return false;
        };

        this.disableTabFocus = function(objArray) {
            $.each(objArray,function(index,$el){
                $el.attr('tabindex','-1');
            });
        };

        this.enableTabFocus = function(objArray) {
            $.each(objArray,function(index,$el){
                $el.removeAttr('tabindex');
            });
        };

        // mobile sub nav max-height must be set/reset to only allow scrolling on menu container depending on the height of the active sub-nav,
        // when all are inactive, max-height is set to device/window height

        let setMobileNavHeightToWindowHeight = function() {
            $subNavs.css('max-height',$window.height());
        };

        let resetMobileNavHeight = function() {
            $subNavs.removeAttr('style');
        };

        let allowActiveSubNavScroll = function($subNav) {
            $subNav.removeAttr('style');
        };

        let enableScrollBounceFix = function() {

            if (webkitOverflowScrollingSupport) {
                $globalHeader.on('touchstart.scrollBounceFix','.' + selectors.mainNavSection,function(e){
                    e.stopPropagation();

                    let $this = $(this);
                    let scrollTop = $this.scrollTop();

                    // If the view is scrolled all the way to the top or bottom, scroll down
                    // or up by 1 pixel to prevent the scroll from reaching the body.
                    if (scrollTop === 0) {
                        $this.scrollTop(1);
                    } else if (scrollTop + $this[0].offsetHeight === $this[0].scrollHeight) {
                        $this.scrollTop(scrollTop - 1);
                    }
                });
            }

        };

        this.openMobileMenu = function() {
            if ( !$mainNavSection.hasClass(dynamicClasses.displayBlock) ){ // displayBlock check to make sure menu is fully closed
                $window.trigger('mobileMenuOpen');
                $mainNavSection.addClass(dynamicClasses.displayBlock);
                _this.disableTabFocus([$topBarLogo,$mobileSearch]);
                window.setTimeout(function(){
                    $globalHeader.addClass(dynamicClasses.menuOpen);
                    utils.fixBody(true);
                    setMobileNavHeightToWindowHeight();
                    $siteWrap.addClass(dynamicClasses.tapToClose);
                    enableScrollBounceFix();
                }, 10);
            }

        };

        this.closeMobileMenu = function() {
            _this.enableTabFocus([$topBarLogo,$mobileSearch]);
            $globalHeader.removeClass(dynamicClasses.menuOpen);
            utils.fixBody(false);
            window.scroll(0,window.scrollY-1); // force scroll fixes odd iOS bug where background is peaking through single pixel line
            resetMobileNavHeight();
            $siteWrap.removeClass(dynamicClasses.tapToClose);
            $window.trigger('mobileMenuClose');

            //close any open sub-navs if menu is closed
            window.setTimeout(function(){
                let $activeSubNav = $('.' + dynamicClasses.subNavActive);
                if ($activeSubNav.length > 0) {
                    _this.closeMobileMenuSubNav($activeSubNav);
                }
                $mainNavSection.removeClass(dynamicClasses.subNavOpen).scrollTop(0).removeClass(dynamicClasses.displayBlock); // scroll to top if scrolled down
            },constants.mobileTransitionSpeed);
        };

        this.openMobileMenuSubNav = function($currentNavItem) {
            if (utils.getViewportSize() === 'small') {
                $navItemAs.off('click.openMobileMenuSubNav'); // unbind to prevent issue of blank subnav on fast-click reopen
                $mainNavSection.animate({scrollTop: 0}, 90); // scroll to the top of the menu before opening the subnav

                $currentNavItem.addClass(dynamicClasses.displayBlock);

                window.setTimeout(function(){
                    $currentNavItem.addClass(dynamicClasses.subNavActive).parents('.' + selectors.mainNavSection).addClass(dynamicClasses.subNavOpen);
                    allowActiveSubNavScroll($currentNavItem.find('.' + selectors.subNav));
                },10);
            }
        };

        this.bindOpenMobileMenuSubNav = function() {
            $navItemAs.on('click.openMobileMenuSubNav',function(){
                let $currentNavItem = $(this).parent('.' + selectors.navItem);
                _this.openMobileMenuSubNav($currentNavItem);
            });
        };

        // close the sub-nav in the mobile menu, take the parent nav item as an argument
        this.closeMobileMenuSubNav = function($parentNavItem) {
            $navItemAs.off('click.openMobileMenuSubNav');  // unbind to prevent issue of blank subnav on fast-click reopen
            $parentNavItem.removeClass(dynamicClasses.subNavActive).parents('.' + selectors.mainNavSection).removeClass(dynamicClasses.subNavOpen);
            setMobileNavHeightToWindowHeight();

            window.setTimeout(function(){
                $parentNavItem.removeClass(dynamicClasses.displayBlock);
            },constants.mobileTransitionSpeed);

            window.setTimeout(function(){
                _this.bindOpenMobileMenuSubNav(); // bind open again after reasonable delay of close to prevent issues with open
            },constants.mobileTransitionSpeed + 350);
        };

        this.bindMainEventListeners = function() {

            // destop hover on navItem, show flyout subnav
            $navItems.on('touchstart mouseenter',function(e){
                if (utils.getViewportSize() !== 'small') {

                    $('.'+dynamicClasses.hoverOpen).removeClass(dynamicClasses.hoverOpen); // in case anything is currently focused

                    let $currentNavItem = $(this);

                    if (e.type == 'touchstart' && webkitOverflowScrollingSupport){ // iOS fix for no mouseleave event
                        e.stopImmediatePropagation(); // stops from registering mouseenter, since mouseleave can't be triggered by simply tapping outside the nav item

                       $(document).on('touchstart.navItem',function(e){ // allow user to close subnav by tapping outside
                            $currentNavItem.trigger('mouseleave');
                            $(document).off('touchstart.navItem');
                        });
                    }


                    $currentNavItem.addClass(dynamicClasses.hoverOpen);

                }
            }).on('mouseleave',function(){
                if (utils.getViewportSize() !== 'small') {
                    $(this).removeClass(dynamicClasses.hoverOpen);
                }
            });

            // On Window Resize, set the mobile nav height, or if viewport > small, close mobile nav
            // and activate or deactivate mobile transitions so mobile nav does not flash when viewport changes
            $window.on('resize',function(){
                utils.debounce(function(){
                    if (utils.getViewportSize() === 'small') {
                        setMobileNavHeightToWindowHeight();
                        activateMobileTransitions();
                    } else {
                        if (_this.isMobileMenuOpen()) {
                            _this.closeMobileMenu();
                        }
                        deactivateMobileTransitions();
                    }
                },100);
            });
        };

        this.bindMobileMenuListeners = function() {

            // Toggle Mobile Menu, slide/close nav drawer
            $menuToggle.on('click',function(){
                if ( !_this.isMobileMenuOpen()) {
                    _this.openMobileMenu();
                } else {
                    _this.closeMobileMenu();
                }
            });

            // Mobile Click navItem, slide open subNav
            _this.bindOpenMobileMenuSubNav();

            // Close Mobile Sub Nav Menu
            $closeSubNav.on('click',function(){
                let $currentNavItem = $(this).parents('.' + selectors.navItem);
                _this.closeMobileMenuSubNav($currentNavItem);
            });

            // On Site body click, close mobile menu
            $body.on('click touchstart touchmove', '.'+ dynamicClasses.tapToClose, function(e){
                e.preventDefault();
                _this.closeMobileMenu();
            });

        };

        this.bindAccessibilityListeners = function() {

            // on click skip-main link, skip to main content and focus
            $skipMain.on('click',function(e){
                e.preventDefault();
                $siteWrap.attr('tabIndex','-1').focus(); //Chrome and IE9 don't allow focusing on non-focusable elements, thus when you hit tab again, it starts from top
            }).on('focus',function(){ // close mobile menu if open
                if (_this.isMobileMenuOpen()) {
                    _this.closeMobileMenu();
                }
            });

            //Tab through: desktop focus on navItemA, show flyout
            $navItemAs.on('focus', function(){
                if (utils.getViewportSize() !== 'small') {
                    $navItems.removeClass(dynamicClasses.hoverOpen);
                    $(this).parent('.' + selectors.navItem).addClass(dynamicClasses.hoverOpen);
                }
            });

            // remove hover open state when focus out of nav item dropdowns
            $navItems.on('focusout',function(){
                window.setTimeout(function(){ // timeout allows activeElement to update
                    if ( $(document.activeElement).parents('.' + selectors.navItem).length === 0 ) {
                        $navItems.removeClass(dynamicClasses.hoverOpen);
                    }
                },50);
            });

            // close the mobileMenu if tabbing out of the mobile menu
            $locationSignInA.last().on('blur',function(){
                window.setTimeout(function(){ // timeout allows activeElement to update
                    if (_this.isMobileMenuOpen() && $(document.activeElement).parents('.' + selectors.mainNavSection).length === 0) {
                        _this.closeMobileMenu();
                    }
                },50);
            });

            // close the sub nav if tabbing out
            if (!webkitOverflowScrollingSupport) { // causes menu to immediately close in iOS, disabling this accessibility tab through helper
                $subNavs.on('focusout',function(){

                    let $currentParentNavItem = $(this).parents('.' + selectors.navItem);

                    window.setTimeout(function(){ // timeout allows activeElement to update
                        if ( $(document.activeElement).parents('.' + selectors.subNav).length === 0 ) {
                            _this.closeMobileMenuSubNav($currentParentNavItem);
                        }
                    },50);
                });
            }

        };

    }

    name() {

        return "GlobalNavigation";
    }

    init() {
        this.bindMainEventListeners();
        this.bindMobileMenuListeners();
        this.bindAccessibilityListeners();
        this.activateMobileTransitions();
    }

    openMobileMenu() {
        this.openMobileMenu();
    }
    closeMobileMenu() {
        this.closeMobileMenu();
    }

    isMobileMenuOpen() {
        return this.isMobileMenuOpen();
    }

    getSelectors() {
        return this.selectors;
    }
}


export default GlobalNavigation;
/* jshint esnext: true */

import GlobalNavigation from './GlobalNavigation.es6';

class UniversalSubnavigation {
	constructor($, Utils) {
        let _this = this;

        let utils = new Utils();
        let globalNav = new GlobalNavigation($, Utils);

        let selectors = {
            tab: 'tab',
            navItem: 'nav-item',
            navItemA: 'nav-item a',
            subNav: 'sub-nav',
            dropdown: 'dropdown',
            scrollCover: 'scroll-cover',
            scrollContainer: 'scroll-container',
            scrollContent: 'scroll-content',
            closeSubNav: 'close-sub-nav',
            active: 'active' // active nav item class
        };

        let dynamicClasses = {
            displayBlock: '_display-block',
            hoverOpen: '_hover-open',
            subNavOpen: '_sub-nav-open',
            maskLeft: '_mask-left',
            maskRight: '_mask-right'
        };

        let $secondaryNav = $('.secondary-nav'),
            $body = $('body'),
            $navItems = $secondaryNav.find('.' + selectors.navItem),
            $navItemAs = $secondaryNav.find('.' + selectors.navItemA),
            $subNavs = $secondaryNav.find('.' + selectors.subNav),
            $dropdown = $secondaryNav.find('.' + selectors.dropdown),
            $scrollCover = $secondaryNav.find('.' + selectors.scrollCover),
            $scrollContainer = $secondaryNav.find('.' + selectors.scrollContainer),
            $scrollContent = $secondaryNav.find('.' + selectors.scrollContent),
            $closeSubNav = $secondaryNav.find('.' + selectors.closeSubNav),
            navHeight = $scrollContainer.height(),
            $window = $(window);

        let scrollContainerScrollX = 0; // cache the scroll Container scroll

        // Test if webkit-overflow-scrolling is supported
        let webkitOverflowScrollingSupport = utils.webkitOverflowScrollingSupport();

        // because of overflow-y hidden, we have to manually position the absolutely position flyout to the relatively postioned seconary nav container
        this.positionFixedSubNav = function($currentNavItem){
            if ( utils.getViewportSize() === 'medium' && $currentNavItem.length > 0) {

                // calc by getting position, subtracting the margin left of secondary Nav and 30px padding in subnav
                let margin = parseInt($secondaryNav.css('marginLeft'),10);
                let left = $currentNavItem[0].getBoundingClientRect().left - margin - 30;

                // set the leftMax to prevent positioning of subNav off the screen
                let leftMax = $window.width() - (margin * 2) - $currentNavItem.children('.'+selectors.subNav).width() - 20;

                // don't let subnav get positioned off screen
                if ( left < 0 ) {
                    left = 0;
                } else if (left > leftMax) {
                    left = leftMax;
                }

                $currentNavItem.find('.'+selectors.subNav).css({'left': left + 'px'});

            } else {

                $currentNavItem.find('.'+selectors.subNav).removeAttr('style');

            }
        };

        // since the width of the sub nav can't be determined from the title due to position relative to parent (sec nav),
        // // we have to set a min width based on the width of the nav item
        let setMinWidthSubNav = function($currentNavItem){
            let minWidth = $currentNavItem.children('a').width();
            $currentNavItem.find('.' + selectors.subNav).css('min-width', minWidth+'px');
        };

        this.addScrollMask = function(){
            let scrollLeft = $scrollContainer.scrollLeft();

            if ( scrollLeft > 0 ) {
                $scrollCover.addClass(dynamicClasses.maskLeft);

            } else {
                $scrollCover.removeClass(dynamicClasses.maskLeft);
            }
            if ( scrollLeft < $scrollContent[0].offsetWidth - $scrollContainer[0].offsetWidth - 2){
                $scrollCover.addClass(dynamicClasses.maskRight);
            } else {
                $scrollCover.removeClass(dynamicClasses.maskRight);
            }

        };

        this.centerScrollNav = function(duration){
            if (utils.getViewportSize() !== 'large' && $scrollContent.width() > $scrollContainer.width()) {
                let $activeNavItem = $navItems.filter('.'+selectors.active),
                    scrollLeft = $activeNavItem[0].offsetLeft + ($activeNavItem[0].offsetWidth + $activeNavItem[0].style.marginLeft)/2 - $window.width()/2;

                if (scrollLeft > 0) {
                    $scrollContainer.animate({'scrollLeft':scrollLeft}, duration || 250);
                }
            }
        };

        // overflow scroll container in in-page secondary nav causing width calc issues
        this.setWidthScrollContainer = function(bool) {
            if ($scrollContainer.length < 1) { // check if scroll container exists on page
                return false;
            }
            if (bool) {
                $scrollContainer.css('max-width',$window.width() + 'px');
            } else {
                $scrollContainer.css('max-width','inherit');
            }
        };

        this.openMobileFlyout = function($currentNavItem){
            $scrollContainer.css('-webkit-overflow-scrolling','auto'); //iOS fix to prevent flash of flyout"
            window.setTimeout(function(){
                $currentNavItem.addClass(dynamicClasses.displayBlock);
            },30);
            window.setTimeout(function(){
                $currentNavItem.addClass(dynamicClasses.subNavOpen);
            },10);
            window.setTimeout(function(){
                scrollContainerScrollX = $scrollContainer.scrollLeft(); // cache the scroll left before fixing body
                $scrollContainer.css('overflow-x','hidden'); // so horizontal scroll bar beneath flyout upon fixing body
                utils.fixBody(true); // fix the body
            },120);
        };

        this.closeMobileFlyout = function(){
            utils.fixBody(false); // fix the body
            $navItems.removeClass(dynamicClasses.subNavOpen);
            $scrollContainer.css('overflow-x','auto'); // first reset overflow-x
            $scrollContainer.scrollLeft(scrollContainerScrollX); // re-set the scroll left of the scroll container
            window.setTimeout(function(){
                $navItems.removeClass(dynamicClasses.displayBlock);
            },120);
            $scrollContainer.removeAttr('style'); // finally remove the -webkit-overflow-scrolling property
        };

        this.isMobileFlyoutOpen = function(){
            if ( $navItems.filter('.'+dynamicClasses.subNavOpen).length > 0) {
                return true;
            }
            return false;
        };

        this.bindMainEventListeners = function() {

            $navItems.on('touchstart mouseenter', function(e){
                if(utils.getViewportSize() !== 'small') {

                    $('.'+dynamicClasses.hoverOpen).removeClass(dynamicClasses.hoverOpen); // close any hovers globally (in case global header is hovered)

                    let $currentNavItem = $(this);

                    if ($currentNavItem.hasClass(selectors.dropdown)) {

                        if (e.type == 'touchstart' && webkitOverflowScrollingSupport){ // iOS fix for no mouseleave event
                            e.stopImmediatePropagation(); // stops from registering mouseenter, since mouseleave can't be triggered by simply tapping outside the nav item

                           $(document).on('touchstart.navItem',function(e){ // allow user to close subnav by tapping outside
                                if(e.target.tagName !== 'A'){
                                    $currentNavItem.trigger('mouseleave');
                                    $(document).off('touchstart.navItem');
                                }
                                return true;
                            });
                        }

                        _this.positionFixedSubNav($currentNavItem);
                        setMinWidthSubNav($currentNavItem);

                    }

                    $(this).addClass(dynamicClasses.hoverOpen);
                }
            }).on('mouseleave', function(){
                $(this).removeClass(dynamicClasses.hoverOpen);
            });

            // dropdown mobile flyout behavior
            $('.dropdown').on('click',function(){ //on mobile click, open a full-screen flyout
                if(utils.getViewportSize() === 'small') {
                    let $currentNavItem = $(this);
                    $currentNavItem.find('.'+selectors.subNav).removeAttr('style');
                    _this.openMobileFlyout($currentNavItem);
                }
            });

            // close mobile fixed sub nav
            $closeSubNav.on('click',function(e){
                e.preventDefault();
                e.stopPropagation(); // to prevent re-open of subNav
                _this.closeMobileFlyout();
            });

            // scroll nav mask
            $scrollContainer.on('scroll',function(){
                utils.debounce(function(){
                    if (globalNav.isMobileMenuOpen()){
                        globalNav.closeMobileMenu();
                    }
                    // reposition any open flyout if user starts to scroll
                     _this.positionFixedSubNav($secondaryNav.find('.'+dynamicClasses.hoverOpen));
                    _this.addScrollMask();
                },60);
            });

            // scroll container width fix on mobile menu open/close
            $window.on('mobileMenuOpen', function(){
                    _this.setWidthScrollContainer(true);
                }).on('mobileMenuClose', function(){
                    _this.setWidthScrollContainer(false);
                });

            // On Window Resize, set the mobile nav height, or if viewport > small, close mobile nav
            // and activate or deactivate mobile transitions so mobile nav does not flash when viewport changes
            $window.on('resize',function(){
                utils.debounce(function(){
                    _this.centerScrollNav(50);
                    _this.addScrollMask();

                    if (utils.getViewportSize() !== 'small') {
                        //close any open mobile subNav
                        if (_this.isMobileFlyoutOpen()) {
                            _this.closeMobileFlyout();
                        }
                    }

                    let $openNavItem = $navItems.filter('.' + dynamicClasses.hoverOpen);

                    if ( $openNavItem.length > 0) {
                        window.setTimeout(function(){ // give time for orientation to update
                            _this.positionFixedSubNav($openNavItem); // reposition the subnav if fixed, for touch device orientation change
                        },100);
                    }

                },100);
            });

            // scroll center active tab after page load if tablet or mobile
            $(function(){
                window.setTimeout(function(){
                    _this.addScrollMask();
                    _this.centerScrollNav();
                 }, 350);
            });
        };

        this.bindAccessibilityListeners = function() {

            // desktop tab accessibility, dropdown
            $navItemAs.on('focus', function(){
                let $currentNavItem = $(this).parent('.'+selectors.navItem);
                if (utils.getViewportSize() !== 'small') {

                    let delay = 0; // no delay for desktop

                    if (utils.getViewportSize() !== 'large') {
                        delay = 200; // set delay for tablet
                    }
                    setTimeout(function(){ // in case the focused item is off screen, allow the browser to bring it on screen
                        $currentNavItem.addClass(dynamicClasses.hoverOpen).siblings().removeClass(dynamicClasses.hoverOpen);
                        _this.positionFixedSubNav($currentNavItem);
                    }, delay);

                } else if ($navItems.filter('.'+dynamicClasses.subNavOpen).length > 0 ) { // if user does not tab into flyout

                    window.setTimeout(function(){ // timeout allows activeElement to update
                        if ( $(document.activeElement).parents('.' + selectors.subNav).length === 0) {
                            _this.closeMobileFlyout();
                        }
                    },50);

                }

            });

            // close the sub nav if tabbing out
            $subNavs.on('focusout',function(){
                let $currentParentNavItem = $(this).parents('.' + selectors.navItem);

                window.setTimeout(function(){ // timeout allows activeElement to update
                    if ( $(document.activeElement).parents('.' + selectors.subNav).length === 0) {
                        $currentParentNavItem.removeClass(dynamicClasses.hoverOpen);

                        if (_this.isMobileFlyoutOpen()) {
                            _this.closeMobileFlyout();
                        }
                    }
                },50);
            });
        };

    }

    name() {
        return "UniversalSubnavigation";
    }

    init() {
        this.bindMainEventListeners();
        this.bindAccessibilityListeners();
    }

    setWidthScrollContainer(bool) {
        this.setWidthScrollContainer(bool);
    }
}

export default UniversalSubnavigation;
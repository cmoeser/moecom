/* jshint esnext: true */

import Promise from '../external/es6-promise.min';

//TODO: make Promise = Promise.Promise

class Utils {
	constructor() {
        let _this = this;

        let dynamicClasses = {
            fixed: '_fixed' // class for fixed body
        };

        this.constants = {
            breakpoints: {
                small: 767,
                medium: 1180,
                large: 1440
            }
        };

        this.scrollY = 0; // var to cache current window scroll

        // returns 'large', 'medium', or 'small' by querying a CSS set 'content' property on psuedo-element on body, see _global.scss
        this.getViewportSize = function() {
            return window.getComputedStyle(document.querySelector('body'), ':before').getPropertyValue('content').replace(/['"]/g,'');
        };
        this.loadUrl = function(url) {
            return new Promise.Promise(function(resolve, reject) {
                var req = new XMLHttpRequest();
                req.open('GET', url);
                req.onload = function() {
                    if(req.status === 200){
                        resolve(req.response);
                    }else{
                        reject(Error(req.statusText));
                    }
                };
                req.onerror = function(){
                    reject(Error('Network Error'));
                };
                req.send();
            });
        };
        this.loadScript = function(url) {
            return new Promise.Promise(function(resolve, reject) {
                var ready = false,
					tag = document.getElementsByTagName('script')[0],
					script = document.createElement('script');
                script.type = 'text/javascript';
               script.src = url;
                script.async = true;
                script.onload = script.onreadystatechange = function() {
                    if (!ready && (!this.readyState || this.readyState == 'complete' || this.readyState == 'loaded')) {
                        ready = true;
                        resolve(this);
                    }
                };

                script.onerror = script.onabort = reject;
                //var obj = JSON.parse(script);
                console.log('Script: '+script); 
                
                //tag.parentNode.insertBefore(script, tag); 
            });
        };
        this.testFeature = function(feature) {
            if(window[feature] === undefined) {
            	return false;
            }
            return true;
        };
        this.debounce = function(func, wait, immediate) {
            let timeout;
            let context = this, args = arguments;
            let later = function() {
                timeout = null;
                if (!immediate) {
                    func.apply(context, args);
                }
            };
            let callNow = immediate && !timeout;
            clearTimeout(timeout);
            timeout = setTimeout(later, wait || 100);
            if (callNow) {
                func.apply(context, args);
            }
        };

        let stopEvent = function(e) {
            e.preventDefault();
            e.stopPropagation();
        };

        this.stopScroll = function(bool) {
            if (bool) {
                window.addEventListener('wheel', stopEvent);
                window.addEventListener('mousewheel', stopEvent);
            } else {
                window.removeEventListener('wheel', stopEvent);
                window.removeEventListener('mousewheel', stopEvent);
            }
        };
        this.getQueryVariable = function (variable) {
               var query = window.location.search.substring(1);
               var vars = query.split("&");
               for (var i=0;i<vars.length;i++) {
                       var pair = vars[i].split("=");
                       if(pair[0] == variable){return pair[1];}
               }
               return(false);
        };

		// prevent scrolling on body, pass true to fix body, false to disable
        this.fixBody = function(bool) {
            if (bool) {
                _this.scrollY = window.scrollY; //cache scrollY
                document.body.className = document.body.className + ' ' + dynamicClasses.fixed;
            } else {
                let regex = new RegExp(' ' + dynamicClasses.fixed,'g');
                document.body.className = document.body.className.replace(regex,''); // replace all instances
                window.scroll(0,_this.scrollY); // set Y scroll to scrollY
            }
        };
        // Test if webkit-overflow-scrolling is supported
        this.webkitOverflowScrollingSupport = function(){
            let testDiv = document.createElement('div');
            document.documentElement.appendChild(testDiv);
            testDiv.style.WebkitOverflowScrolling = 'touch';
            let bool = ('getComputedStyle' in window && window.getComputedStyle(testDiv)['-webkit-overflow-scrolling'] === 'touch');
            document.documentElement.removeChild(testDiv);
            return bool;
        };

        //this.imagesLoaded = require('imagesloaded');
    }

    getViewportSize() {
        return this.getViewportSize();
    }

	loadUrl(url) {
		this.loadUrl(url);
	}

	loadScript(url) {
		this.loadScript(url);
	}

	testFeature(feature) {
		this.testFeature(feature);
	}

    debounce(func, wait, immediate) {
        this.debounce(func, wait, immediate);
    }

    fixBody(bool){
        this.fixBody(bool);
    }

    stopScroll(bool){
        this.stopScroll(bool);
    }

    webkitOverflowScrollingSupport(){
        return this.webkitOverflowScrollingSupport();
    }

    constants() {
        return this.constants;
    }

	name() {
		return "Utils";
	}
}
export default Utils;
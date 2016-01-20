import $ from './external/jquery-1.11.3.min';
import Utils from './modules/Utils.es6';
import Main from './modules/Main.es6';
import GlobalNavigation from './modules/GlobalNavigation.es6';
import UniversalSubnavigation from './modules/UniversalSubnavigation.es6';

let app = new Main($, [
	    GlobalNavigation,
		UniversalSubnavigation
	], $('[data-module]'), Utils);

app.init(); 
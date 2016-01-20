import $ from '../global/external/jquery-1.11.3.min';
import Utils from '../global/modules/Utils.es6';
import Main from '../global/modules/Main.es6';
import Artwork from './modules/Artwork_images.es6';


let app = new Main($, [
		Artwork
	], $('[data-module]'), Utils);

app.init(); 
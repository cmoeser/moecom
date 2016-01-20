class Main {
	constructor($, imports, modules, Utils) {
		this._imports = imports;
		this._modulesLoaded = {};
 
		let getModulesOnPage = function() {
			let _modules = [],
                module,
                i = 0;
			for (i; i < modules.length; i++){
				module = $(modules[i]).data('module');
				_modules.push(module);
			}
			return _modules;
		};

		this._instantiateModules = function(modules) {
			var modulesOnPage = getModulesOnPage(),
                module,
				name,
				loaded = [],
                i = 0;

			for (i; i < modules.length; i++){
				name = Object.create(modules[i]).prototype.name();
				if(modulesOnPage.indexOf(name) > -1 && loaded.indexOf(name) === -1){
					module = new modules[i]($, Utils); //init module. pass jQuery.
					module.init();
					loaded.push(name);
					this._modulesLoaded[name] = module;
				}
			}
			this._modulesLoaded._length = loaded.length;
		};

		this._browserHandler = function() {
			var ua = navigator.userAgent.toLowerCase(),
			isAndroid = ua.indexOf("android") > -1,
			isIpad = ua.indexOf('ipad') > -1,
			isIphone = ua.indexOf('iphone') > -1,
			isIE9 = ua.indexOf("msie 9") > -1,
			isIE = ua.indexOf("msie") > -1 || ua.indexOf("trident") > -1;

			if(isAndroid) {
				document.body.className += ' android';
			}else if (isIpad) {
				document.body.className += ' ipad';
			}else if (isIphone) {
				document.body.className += ' iphone';
			}else if(isIE) {
				document.body.className += ' ie';

				if (isIE9) {
					document.body.className += ' ie9';
				}
			}
		};
	}

	modulesLoaded() {
		return this._modulesLoaded;
	}

	init() {
		this._browserHandler();
		this._instantiateModules(this._imports);
	}
}
export default Main;
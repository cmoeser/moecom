/* Generated by Babel */
'use strict';

function _interopRequireDefault(obj) { return obj && obj.__esModule ? obj : { 'default': obj }; }

var _globalExternalJquery1113Min = require('../global/external/jquery-1.11.3.min');

var _globalExternalJquery1113Min2 = _interopRequireDefault(_globalExternalJquery1113Min);

var _globalModulesUtilsEs6 = require('../global/modules/Utils.es6');

var _globalModulesUtilsEs62 = _interopRequireDefault(_globalModulesUtilsEs6);

var _globalModulesMainEs6 = require('../global/modules/Main.es6');

var _globalModulesMainEs62 = _interopRequireDefault(_globalModulesMainEs6);

var _modulesArtwork_imagesEs6 = require('./modules/Artwork_images.es6');

var _modulesArtwork_imagesEs62 = _interopRequireDefault(_modulesArtwork_imagesEs6);

var app = new _globalModulesMainEs62['default'](_globalExternalJquery1113Min2['default'], [_modulesArtwork_imagesEs62['default']], (0, _globalExternalJquery1113Min2['default'])('[data-module]'), _globalModulesUtilsEs62['default']);

app.init();
class Artwork {
	constructor($, Utils) {
		var $window = $(window);

		this.currentCat = 0;
		this.thumbImgs = [];
		this.fullImgs = [];
		this.currentImg = 0;

		var $win = $(window);
        let $doc = $(document);
        let self = this;
		let utils = new Utils();
        
        let categories = ['painting'];

		let selectors = {
			thumbnails: '.thumbnails',
			artworkImage: '.artworkImage'
		};

		let objects = {
			thumbnails: $(selectors.thumbnails),
			fullImage: $(selectors.artworkImage)
        };

    	this.loadArtworkJSON = function() {
		    return $.getJSON('resources/includes/painting.json').then(function(data) {
		        return data;
			});
		}; 
		
		this.processJSON = function(data){
			let json = data;

			for (var key in json) {
		       if (json.hasOwnProperty(key)) {
		       	    this.thumbImgs.push(json[key].thumbLoc);
				    let artwork = new Object({
						'imgLoc':json[key].imgLoc,  
						'imgTitle':json[key].imgTitle,
						'imgMedium':json[key].imgMedium,
						'imgDate':json[key].imgDate,
						'imgDimensions':json[key].imgDimensions,
						'imgAvail':json[key].imgAvail					
					});
				   this.fullImgs.push(artwork);

		       }
            }
			this.setUrlVar('img');
 			if(utils.getViewportSize() !== 'small')
			this.createThumbnails();          
		};

		this.createThumbnails = function(){
            let html = this.generateThumbs('thumbnails');
            objects.thumbnails.append(html);
            this.setFullImg();
		};

		this.generateThumbs = function() {   	
			let thumbImgs = this.thumbImgs,
			htmlObj = document.createElement('div');

			for (let i=0;i<thumbImgs.length;i++) {
		       	    let img = new Image(),
					src = "resources/images/"+categories[this.currentCat]+"/"+thumbImgs[i],
					alt = this.fullImgs[i].imgTitle;
					let $img = $(img).attr('alt', alt);
				   $img.attr('src', src);
				  $(htmlObj).append($img);
		       }
            return htmlObj;
        };
		
		this.setFullImg = function(){
			let fullImg = this.fullImgs[this.currentImg], 
			    img =  new Image(),
					src = "resources/images/"+categories[this.currentCat]+"/"+fullImg.imgLoc,
					alt = fullImg.imgTitle;
					let $img = $(img).attr('alt', alt);
				   $img.attr('src', src);
               
			 objects.fullImage.append($img);
		};

		this.setUrlVar = function (urlVar){
			let setvar = utils.getQueryVariable(urlVar);
			if(setvar == 'cat'){
			    //Check for non numbers, undefined and irellivant numbers
			    this.currentCat = (setvar) ? (!isNaN(setvar) ? (setvar<categories.length ? setvar : 0) :0)  : 0;
			}else{
				 this.currentImg = (setvar) ? (!isNaN(setvar) ? (setvar<this.fullImgs.length ? setvar : 0) :0)  : 0;
			}
		};
		

	}
	name() {
		return "Artwork";
	}

	init() {
	   this.setUrlVar('cat');

	   var $scope = this;
       this.loadArtworkJSON().done($scope, function(data) {
		    $scope.processJSON(data);
		});
	}
}
export default Artwork;
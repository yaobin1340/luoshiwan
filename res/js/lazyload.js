(function(){
	
	 
	
	
	$(document).ready(function(e) {
		
		setTimeout(function(){
        $(".lazy").each(function(index){
           var _height = $(window).height() + $(window).scrollTop();
           if($(this).offset().top<=_height){
                 $(this).attr("src",$(this).attr('dataimg'));
                  }
      }); },300); 
	  
	 
	  
    });
	
	
	     $(window).on("scroll", function(){
			 
		setTimeout(function(){	 
        $(".lazy").each(function(index){
           var _height = $(window).height() + $(window).scrollTop();
           if($(this).offset().top<=_height){
                 $(this).attr("src",$(this).attr('dataimg'));
                  }
        });},500);
	  
	  
	  
    });
 
}());


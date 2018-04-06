/**
 * Gordo Expandable Menu
 * @version 1.0.0
 * @author G.Breant
 */

(function($){
    $.fn.extend({ 
        gordoExpandableMenu: function(options){
            // OPTIONS
            var defaults = {
                childrenSelector:'ul',
                handleSelector: '> a',
                activeItemSelector: '.current-menu-item',
                defaultClosed:true, // minimize at start
                button: '<button></button>',
                button_glyph_open: '+',
                button_glyph_closed: '-',
            };
            var options =  $.extend(defaults, options);

            // FOR EACH MATCHED ELEMENT
            return this.each(function() {
                
                var $item = $(this);
                var $children = $item.find(options.childrenSelector);
                var $handle = $item.find(options.handleSelector);
                var is_active = $item.find(options.activeItemSelector).length;
                
                if ( !$children.length ) return;
                
                var toggleState = function(bool) {

                    $item.toggleClass('gordoExpandableMenuMinimized',bool);

                    //switch content
                    if ( $item.hasClass('gordoExpandableMenuMinimized') ){
                        $button.html(options.button_glyph_open);
                        $children.hide();
                    }else{
                        $button.html(options.button_glyph_closed);
                        $children.show();
                    }
                };
                
                if ( !$item.hasClass('gordoExpandableMenu') ) { //init
                    
                    $item.addClass('gordoExpandableMenu');
                
                    //add button
                    var $button = $(options.button);
                    $button.addClass('gordoExpandableMenuBt');
                    $handle.append($button);

                    //menu click
                    $button.on('click', function (e) {
                        e.preventDefault();
                        toggleState();

                    });
                    
                    //init state
                    var closeInit = ( options.defaultClosed && !is_active );
                    toggleState(closeInit);

                }
                
                
                
            });
        }
    });
})(jQuery); // End jQuery Plugin

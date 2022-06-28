/**
 * jQuery Extension Functions
 */

 jQuery.fn.hcenter = function()
 {
     
     try
     {
         this.css({
             'display': 'flex',
             'justify-content': 'center'
         });
     }
     catch(error)
     {
         console.log('hcenter: ' + error);
     }
 
     return this;
 }
 
 /// <summary>
 ///     Horizontally center an element within its immediate parent. We assume the parent element is
 ///     absolutely positioned within the document with fixed size.
 /// </summary>
 /// <param name="width"> 
 ///     The (needed) width of the element to horizontally center. It must be less
 ///     than the width of the parent within which to center it.
 /// </param>
 /// <param name="relativeElement">
 ///     The parent element within which to horizontally center this one. It must be fixed within
 ///     the document and its size must be set. It defaults to 'body'.
 /// </param>
 jQuery.fn.hcenter2 = function(width=0, relativeElement=null)
 {
     let parent = relativeElement;
     let w = width;
     try 
     {
         if(!parent)
         {
             parent = jQuery('body');
         }
         if(w === 0)
         {
             w = jQuery(this).width();
         }
 
         let position = parent.position();
         let sum = 0;
         if(parent.width() < w)
         {
             // No jumbling up things, so return
             return this;
         }
         else 
         {
             sum = (parent.width() - w)/2;
         }
 
         this.css({
             position: 'absolute',
             left: (position.left + sum)
         });
     }
     catch(error)
     {
         console.log('hcenter2: ' + error);
     }
 
     return this;
 }
 
 jQuery.fn.vcenter = function()
 {
     try
     {
         this.css({
             'display': 'flex',
             'align-items': 'center'
         });
     }
     catch(error)
     {
         console.log('vcenter: ' + error);
     }
 
     return this;
 }
 
 /// <summary>
 ///     Vertically center an element within its immediate parent. We assume the parent element is
 ///     absolutely positioned within the document with fixed size.
 /// </summary>
 /// <param name="height"> 
 ///     The (needed) height of the element to vertically center. It must be less
 ///     than the height of the parent within which to center it.
 /// </param>
 /// <param name="relativeElement">
 ///     The parent element within which to vertically center this one. It must be fixed within
 ///     the document and its size must be set. It defaults to 'body'.
 /// </param>
 jQuery.fn.vcenter2 = function(height=0, relativeElement=null)
 {
     let parent = relativeElement;
     let h = height;
     try 
     {
         if(!parent)
         {
             parent = jQuery(window);
         }
         if(h === 0)
         {
             h = jQuery(this).height();
         }
 
         let position = parent.position();
         let sum = 0;
         if(parent.height() < h)
         {
             // No jumbling up things, so return
             return this;
         }
         else 
         {
             sum = (parent.height() - h)/2;
         }
 
         this.css({
             position: 'absolute',
             top: (position.top + sum)
         });
     }
     catch(error)
     {
         console.log('vcenter2: ' + error);
     }
 
     return this;
 }
 
 jQuery.fn.hvcenter = function()
 {
     try
     {
         if(this.parent())
         {
             //console.log('Position: ' + this.parent().css('position'));
             //this.parent().css('position', 'relative');
         }
         
         this.css({
             'position': 'absolute',
             'top': '50%',
             'left': '50%',
             'transform': 'translate(-50%, -50%)'
         });
     }
     catch(error)
     {
         console.log('hvcenter: ' + error);
     }
 
     return this;
 }
 
 /// <summary>
 ///     Center an element within its immediate parent. We assume the parent element is
 ///     absolutely positioned within the document with fixed size.
 /// </summary>
 /// <param name="width"> 
 ///     The (needed) width of the element to center. It must be less
 ///     than the width of the parent within which to center it.
 /// </param>
 /// <param name="height"> 
 ///     The (needed) height of the element to center. It must be less
 ///     than the height of the parent within which to center it.
 /// </param>
 /// <param name="relativeElement">
 ///     The parent element within which to center this one. It must be fixed within
 ///     the document and its size must be set. It defaults to 'body'.
 /// </param>
 jQuery.fn.hvcenter2 = function(width=0, height=0, relativeElement=null)
 {
     let parent = relativeElement;
     let h = height;
     let w = width;
     try 
     {
         if(!parent)
         {
             parent = jQuery('body');
         }
         if(h === 0)
         {
             h = jQuery(this).height();
         }
         if(w === 0)
         {
             w = jQuery(this).width();
         }
 
         let position = parent.position();
         let sumh = 0, sumw = 0;
         if(parent.height() < h)
         {
             // No jumbling up things, so return
             return this;
         }
         else 
         {
             sumh = (parent.height() - h)/2;
         }
         if(parent.width() < w)
         {
             // No jumbling up things, so return
             return this;
         }
         else 
         {
             sumw = (parent.width() - w)/2;
         }
 
         //console.log(`h: ${h}; w: ${w}; sumh: ${sumh}; sumw: ${sumw}`);
 
         this.css({
             position: 'absolute',
             top: (position.top + sumh),
             left: (position.left + sumw)
         });
     }
     catch(error)
     {
         console.log('hvcenter2: ' + error);
     }
 
     return this;
 }
 
 jQuery.fn.center = function(){
     var $foo = jQuery(this);
     $foo.css({
         'position' : 'absolute',
         'left' : '50%',
         'top' : '50%',
         'margin-left' : -$foo.width()/2,
         'margin-top' : -$foo.height()/2
     });
 
     return $foo;
 }

 // see https://stackoverflow.com/questions/3514784/what-is-the-best-way-to-detect-a-mobile-device
jQuery.fn.isMobile = function()
{
    let isMob = false; //initiate as false
    try
    {
        // device detection
        if(/(android|bb\d+|meego).+mobile|avantgo|bada\/|blackberry|blazer|compal|elaine|fennec|hiptop|iemobile|ip(hone|od)|ipad|iris|kindle|Android|Silk|lge |maemo|midp|mmp|netfront|opera m(ob|in)i|palm( os)?|phone|p(ixi|re)\/|plucker|pocket|psp|series(4|6)0|symbian|treo|up\.(browser|link)|vodafone|wap|windows (ce|phone)|xda|xiino/i.test(navigator.userAgent) 
        || /1207|6310|6590|3gso|4thp|50[1-6]i|770s|802s|a wa|abac|ac(er|oo|s\-)|ai(ko|rn)|al(av|ca|co)|amoi|an(ex|ny|yw)|aptu|ar(ch|go)|as(te|us)|attw|au(di|\-m|r |s )|avan|be(ck|ll|nq)|bi(lb|rd)|bl(ac|az)|br(e|v)w|bumb|bw\-(n|u)|c55\/|capi|ccwa|cdm\-|cell|chtm|cldc|cmd\-|co(mp|nd)|craw|da(it|ll|ng)|dbte|dc\-s|devi|dica|dmob|do(c|p)o|ds(12|\-d)|el(49|ai)|em(l2|ul)|er(ic|k0)|esl8|ez([4-7]0|os|wa|ze)|fetc|fly(\-|_)|g1 u|g560|gene|gf\-5|g\-mo|go(\.w|od)|gr(ad|un)|haie|hcit|hd\-(m|p|t)|hei\-|hi(pt|ta)|hp( i|ip)|hs\-c|ht(c(\-| |_|a|g|p|s|t)|tp)|hu(aw|tc)|i\-(20|go|ma)|i230|iac( |\-|\/)|ibro|idea|ig01|ikom|im1k|inno|ipaq|iris|ja(t|v)a|jbro|jemu|jigs|kddi|keji|kgt( |\/)|klon|kpt |kwc\-|kyo(c|k)|le(no|xi)|lg( g|\/(k|l|u)|50|54|\-[a-w])|libw|lynx|m1\-w|m3ga|m50\/|ma(te|ui|xo)|mc(01|21|ca)|m\-cr|me(rc|ri)|mi(o8|oa|ts)|mmef|mo(01|02|bi|de|do|t(\-| |o|v)|zz)|mt(50|p1|v )|mwbp|mywa|n10[0-2]|n20[2-3]|n30(0|2)|n50(0|2|5)|n7(0(0|1)|10)|ne((c|m)\-|on|tf|wf|wg|wt)|nok(6|i)|nzph|o2im|op(ti|wv)|oran|owg1|p800|pan(a|d|t)|pdxg|pg(13|\-([1-8]|c))|phil|pire|pl(ay|uc)|pn\-2|po(ck|rt|se)|prox|psio|pt\-g|qa\-a|qc(07|12|21|32|60|\-[2-7]|i\-)|qtek|r380|r600|raks|rim9|ro(ve|zo)|s55\/|sa(ge|ma|mm|ms|ny|va)|sc(01|h\-|oo|p\-)|sdk\/|se(c(\-|0|1)|47|mc|nd|ri)|sgh\-|shar|sie(\-|m)|sk\-0|sl(45|id)|sm(al|ar|b3|it|t5)|so(ft|ny)|sp(01|h\-|v\-|v )|sy(01|mb)|t2(18|50)|t6(00|10|18)|ta(gt|lk)|tcl\-|tdg\-|tel(i|m)|tim\-|t\-mo|to(pl|sh)|ts(70|m\-|m3|m5)|tx\-9|up(\.b|g1|si)|utst|v400|v750|veri|vi(rg|te)|vk(40|5[0-3]|\-v)|vm40|voda|vulc|vx(52|53|60|61|70|80|81|83|85|98)|w3c(\-| )|webc|whit|wi(g |nc|nw)|wmlb|wonu|x700|yas\-|your|zeto|zte\-/i.test(navigator.userAgent.substr(0,4))) 
        { 
            isMob = true;
        }
    }
    catch(error)
    {
        console.log("isMobile: " + error);
    }

    return isMob;
}

// END jQuery Extension Functions

/**
 * Classes
 */

 class HorizontalSpace
 {
     /// <summary>
     ///     Assesses the available horizontal space where a list of items is expected to be.
     /// </summary>
     /// <param name="items_list_selector">
     ///     Selector of the list element where the items will be displayed. Needed to assess initial list item size each'
     /// </param>
     /// <param name="parent_selector">
     ///     Immediate parent jQuery selector so as to find her myself
     /// </param>
     /// <return>
     ///     'horizontal_space_state' property object that has two properties:
     ///      1. list_display_style: it sets the css 'display' prop
     ///      2. display_type: one of these {'flex', 'flex-block-list', 'block-list'}
     /// </return>
     /// NOTE: For optimality let me know where your parent is located given the current actual
     ///       view port condition. Also, your list should be in its worst case span when you construct me for the first time.
     ///       It is optimized to do heavy-lifting computation once in its life-cycle. Hence, if you
     ///       want fresh perspective, construct it again, esp. if parent changes.
     constructor(items_list_selector, parent_selector)
     {
         if(items_list_selector)
         {
             this.parent_selector = parent_selector;
             this.items_list_selector = items_list_selector;
         }
     }
 
     assessViewPortSize(parent_max_width)
     {
         if(typeof(this) === 'undefined')
         {
             console.log("assessViewPortSize: 'this' object is undefined.");
             return;
         }
         if(typeof(parent_max_width) === 'undefined')
         {
             console.log("assessViewPortSize: 'parent_max_width' is undefined.");
             return;
         }
 
         try
         {   
             let parentInnerEndLeftPos = -1;
             let windowInnerEndPos = -1;
             let parentPos = $(this.parent_selector).offset();
             let listPos = $(this.items_list_selector).offset();
             let maxWidth = 0;
             let totalWidth = 0;
             let how_many = 0;
             let listEndLeftPos = 0;
             let pLeftMargin = 0;
             let pLeftPadding = 0;
             let bRightMargin = 0;
             let bRightPadding = 0;
             
             let ps = $(this.parent_selector).css('margin-left');
             if(ps)
             {
                 pLeftMargin = parseFloat(ps.replace(/[^\d\.]+/, ""));
             }
             ps = $(this.parent_selector).css('padding-left');
             if(ps)
             {
                 pLeftPadding = parseFloat(ps.replace(/[^\d\.]+/, ""));
             }
             ps = $('body').css('margin-right');
             if(ps)
             {
                 bRightMargin = parseFloat(ps.replace(/[^\d\.]+/, ""));
             }
             ps = $('body').css('padding-right');
             if(ps)
             {
                 bRightPadding = parseFloat(ps.replace(/[^\d\.]+/, ""));
             }
             
             if(this.list_bar_total_width) // do it once in your life cycle
             {
                 totalWidth = this.list_bar_total_width;
                 maxWidth = this.list_bar_max_with;
                 how_many = this.how_many;
                 listEndLeftPos = this.list_end_left_pos;
 
                 parentInnerEndLeftPos = parentPos.left + pLeftMargin + pLeftPadding + parent_max_width;
                 windowInnerEndPos = window.outerWidth - bRightPadding - bRightMargin;
             }
             else 
             {
                 let lLeftMargin = 0;
                 let lLeftPadding = 0;
                 let lRightMargin = 0;
                 let lRightPadding = 0;
                 let pos = parentPos;
                 if(!pos)
                 {
                     console.log('Yikes! Could not get parent object position. This will taint the results');
                 }
                 else
                 {
                     let s = $(this.items_list_selector).css('margin-left');
                     if(s)
                     {
                         lLeftMargin = parseFloat(s.replace(/[^\d\.]+/, ""));
                     }
                     s = $(this.items_list_selector).css('padding-left');
                     if(s)
                     {
                         lLeftPadding = parseFloat(s.replace(/[^\d\.]+/, ""));
                     }
                     s = $(this.items_list_selector).css('margin-right');
                     if(s)
                     {
                         lRightMargin = parseFloat(s.replace(/[^\d\.]+/, ""));
                     }
                     s = $(this.items_list_selector).css('padding-right');
                     if(s)
                     {
                         lRightPadding = parseFloat(s.replace(/[^\d\.]+/, ""));
                     }
                     
                     windowInnerEndPos = window.outerWidth - bRightPadding - bRightMargin;
                     //console.log(`Parent left margin: ${pLeftMargin}; Parent left padding: ${pLeftPadding}; List left margin: ${lLeftMargin}; List left padding: ${lLeftPadding}; List right margin: ${lRightMargin}; List right padding: ${lRightPadding}`);
                     parentInnerEndLeftPos = pos.left + pLeftMargin + pLeftPadding + parent_max_width;
                     //console.log(`Parent inner end position: ${parentInnerEndLeftPos}`);
                 }
                 if(parentInnerEndLeftPos == -1)
                 {
                     console.log('parentInnerEndLeftPos is invalid. Setting it');
                     parentInnerEndLeftPos = pos.left + pLeftMargin + pLeftPadding + parent_max_width;
                 }
 
                 let selector = $(this.items_list_selector).contents();
                 if(selector.length === 0)
                 {
                     console.log(`selector: ${this.items_list_selector} found no match. DAMN!`);
                 }
                 selector.each((idx, node) => {
                 
                     let width = $(node).width();
                     //console.log('width: ' + width);
                     if(idx == 0)
                     {
                         maxWidth = width;
                     }
                     else
                     {
                         if(width > maxWidth)
                         {
                             maxWidth = width;
                         }
                     }
                     totalWidth += width;
                     // ideally; actually should use listPos
                     listEndLeftPos = pos.left + pLeftMargin + pLeftPadding + lLeftMargin + lLeftPadding + totalWidth + lRightPadding + lRightMargin;
                     if(listEndLeftPos <= parentInnerEndLeftPos)
                     {
                         how_many += 1;
                     }
                 });
                 if(maxWidth>0 && totalWidth>0)
                 {
                     /// NOTE: Keep these definitions together
                     this.list_bar_max_with = maxWidth;
                     this.list_bar_total_width = totalWidth;
                     this.how_many = how_many;
                     this.list_end_left_pos = listEndLeftPos;
                 }
             }
             
             // Actual
             // An attempt to fix flex going flexing over parent on small devices like Infinix X625C
             let flexPossible = true;
             if(listPos && parentPos) 
             {
                 if(listPos.left < parentPos.left || this.mayHaveHorizontalScrollBar())
                 {
                     flexPossible = false;
                 }
             }
             // end Actual
 
             //console.log(`List end left pos (${this.items_list_selector}): ${listEndLeftPos}; Parent inner end left pos: ${parentInnerEndLeftPos}, Window inner end pos: ${windowInnerEndPos}; Flex possible: ${flexPossible}`);
 
             if(parent_max_width > totalWidth && flexPossible && !$(window).isMobile())
             { // Flex
                 this.horizontal_space_state = {
                     list_display_style: {
                         display: 'flex'
                     },
                     display_type: 'flex'
                 };
             }
             else if(how_many>0) // flex + block-list
             {
                 this.horizontal_space_state = {
                     list_display_style: {
                         display: 'flex'
                     },
                     display_type: 'flex-block-list'
                 };
             }
             else // block-list
             {
                 this.horizontal_space_state = {
                     list_display_style: {
                         display: 'flex'
                     },
                     display_type: 'block-list'
                 };
             }
         }
         catch(error)
         {
             console.log("assessViewPortSize: " + error);
         }
     }
 
     mayHaveHorizontalScrollBar()
     {
         let may = false;
 
         try
         {
             let left = 0;
             let scrollL = left + 2;
             $(window).scrollLeft(scrollL);
             let currentScrollL = $(window).scrollLeft();
             //console.log(`Scroll width: ${scrollL}; Current scroll width: ${currentScrollL}`);
             may = (currentScrollL === scrollL) ? true : false;
         }
         catch(error)
         {
             console.log("mayHaveHorizontalScrollBar: " + error);
         }
 
         return may;
     }
 }

// END Classes

function sleep(ms)
{
    return new Promise(resolve => setTimeout(resolve, ms));
}

function scroll_element_into_view(elt_id)
{
    if(elt_id === null)
    {
        return;
    }
    var id = elt_id;
    var $foo = jQuery(`#${id}`),
    elWidth = $foo.width(),
    elHeight = $foo.height(),
    elOffset = $foo.offset();
    jQuery(window)
        .scrollTop(elOffset.top + elHeight);

} // scroll_element_into_view

function scale_obem_site_media()
{
    let clss = 'obem-article-media';
    let elts = document.getElementsByClassName(clss);
    let iilength = elts.length;
    if(iilength > 0)
    {
        let portW = window.innerWidth;
        if(portW > 500)
        {
            let width = (portW*1)/2;
            for(let i = 0; i<iilength; i++)
            {
                elts[i].width = width;
            }
        }
    }
} // scale_obem_site_media

function circle_shape_element(id)
{
    let width = $('#myBackToTopBtn').width();
    $(`#${id}`).css({
        'border-radius' : '50%'
    });
}

// When the user clicks on the button, scroll to the top of the document
function goToTop(e) 
{
    document.body.scrollTop = 0; // For Safari
    document.documentElement.scrollTop = 0; // For Chrome, Firefox, IE and Opera

} // goToTop

function manageGoToTopButton()
{
    // When the user scrolls down 20px from the top of the document, show the button
    window.onscroll = function() {
        //Get the button:
        let mybutton = document.getElementById("myBackToTopBtn");
        if(mybutton)
        {
            if (document.body.scrollTop > 20 || document.documentElement.scrollTop > 20) 
            {
                mybutton.style.display = "block";
            } 
            else 
            {
                mybutton.style.display = "none";
            }
        }
        else 
        {
            //console.log("GotoTop button not found");
        }
    }

    window.addEventListener('click', (e) => {
        if($(e.target).attr('id') === 'myBackToTopBtn')
        {
            goToTop(e);
        }
    });

    window.addEventListener('mouseover', (e) => {
        if($(e.target).attr('id') === 'myBackToTopBtn')
        {
            $('#myBackToTopBtn').css('background-color', '#555');
        }
    });

} // manageGoToTopButton

function manageFixedArticleButton(edit_article_url, id)
{
    window.onclick = (e) => {
        if($(e.target).attr('id') === id)
        {
            window.location = edit_article_url;
        }
    };

    window.addEventListener('mouseover', (e) => {
        if($(e.target).attr('id') === id)
        {
            $(`#${id}`).css('background-color', '#555');
        }
    });

} // manageEditArticleButton

window.addEventListener('pageshow', (event) => {
    //console.log('page is fully loaded. Try and do adjustments ...');
    scale_obem_site_media();
    if(event.persisted)
    {
        //console.log('Page was served from cache');
    }
    else 
    {
        //console.log('Page was served fresh from server');
    }
});

$(document).ready(
    function()
    {
        scale_obem_site_media();
        manageGoToTopButton();
    }
);
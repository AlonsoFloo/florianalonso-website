/* Theme Name: Worthy - Free Powerful Theme by HtmlCoder
 * Author:HtmlCoder
 * Author URI:http://www.htmlcoder.me
 * Version:1.0.0
 * Created:November 2014
 * License: Creative Commons Attribution 3.0 License (https://creativecommons.org/licenses/by/3.0/)
 * File Description: Place here your custom scripts
 */


$(document).ready(function() {
    $(".overlay-container").click(function() {
        var title = $(this).text().trim();
        alert(title);
        ga('send', 'event', 'project', 'seen', title);
    });
});
    
function resumeButton(url) {
    ga('send', 'event', 'resume', 'download');
    var win = window.open(url, '_blank');
    win.focus();
}
/**
 * Copyright © 2009-2014 Rhizome Networks LTD All rights reserved.
 *
 * Created by  Tomer Schilman on 09/07/2015.
 * <p/>
 */

function AddRateLink() {
    var pluginRow = document.getElementById('cg-button');
    var linksDiv = pluginRow.getElementsByClassName('plugin-version-author-uri')
    var aElm = ' | <a href="https://wordpress.org/support/view/plugin-reviews/content-glass-button?rate=5#postform">Rate this plugin</a>';
    if (linksDiv.length > 0) {
        var html = linksDiv[0].innerHTML;
        linksDiv[0].innerHTML = html + aElm;
    }
}
AddRateLink();
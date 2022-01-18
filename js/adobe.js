jQuery(function($) { 

    var adobeapikey = eadAdobe.adobe_api_key;
    if (!adobeapikey) {
        return;
    }

    var e = document.createElement("script");
    e.src = "https://documentcloud.adobe.com/view-sdk/main.js";
    document.head.appendChild(e);

    document.addEventListener("adobe_dc_view_sdk.ready", function () {
        var adobeDCView = new AdobeDC.View({clientId: adobeapikey, divId: "adobe-dc-view"});
        adobeDCView.previewFile(
        {
          content:   {location: {url: $('#adobe-dc-view').data('pdfSrc')}},
          metaData: {fileName: "Nil"}
        });
    });

});

class EadHelper {
    static parseShortcode(shortcodeText) {
        let atts = {};
        shortcodeText.match(/[\w-]+=".+?"/g).forEach( (attr) => {
            attr = attr.match(/([\w-]+)="(.+?)"/);
            atts[attr[1]] = attr[2];
        });
        return atts;
    }

    static isValidMSExtension(url) {
        let validExt = emebeder.msextension.split(',');
        let ext = '.' + url.split('.').pop();
        return jQuery.inArray(ext, validExt) !== -1;
    }
}

export default EadHelper;
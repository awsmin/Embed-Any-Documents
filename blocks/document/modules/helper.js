class EadHelper {
	static getFileSource( url ) {
		let source = 'internal';
		let siteUrl = emebeder.site_url;
		if ( url.indexOf( siteUrl ) === -1 ) {
			if ( url.indexOf( 'dropbox.com' ) !== -1 ) {
				source = 'dropbox';
			} else {
				source = 'external';
			}
		}
		return source;
	}

    static parseShortcode( shortcodeText ) {
        let atts = {};
        shortcodeText.match( /[\w-]+=".+?"/g ).forEach( ( attr ) => {
            attr = attr.match( /([\w-]+)="(.+?)"/ );
            atts[attr[1]] = attr[2];
        });
        return atts;
	}

	static getFileExtension( url ) {
		let ext = '.' + url.split( '.' ).pop();
		let extSplitted = ext.split( '?' );
		return extSplitted[0];
	}

    static isValidMSExtension( url ) {
        let validExt = emebeder.msextension.split( ',' );
        return jQuery.inArray(this.getFileExtension( url ), validExt) !== -1;
	}

	static isPDF( url ) {
		if ( this.getFileExtension( url ) === '.pdf' ) {
			return true;
		} else {
			return false;
		}
	}
}

export default EadHelper;

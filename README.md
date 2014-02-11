# Responsive images service

Image resizing proxy for FT responsive images.

**Placeholder repo to speculatively define the service that we might build.  This service doesn't actually work**

## API reference

API endpoints as follows:

### GET /v1/images/`:mode`/`:uri`,`:uri`,… <br> GET /v1/images/`:mode`?imageset=`:imageset`


Fetch an image or set of images, format and serve them.

<table class='o-techdocs-table'>
  <tr>
    <th>Param</th><th>Where</th><th>Description</th>
  </tr><tr>
    <td><code>mode</code></td>
    <td>URL</td>
    <td>
        How output should be packaged.
        <dl>
            <dt>raw</dt><dd> Output raw image data.  Requests for multiple images must will be output as a sprite in which the images are tiled in a single horizontal line. Per-image `format` override not available for this output mode (because you can't output an image that is part PNG, part JPEG!)</dd>
            <dt>css-sprite</dt><dd> Output a CSS spritesheet containing the images as data URIs attached to classes with names matching the input identifiers (as closely as possible)</dd>
            <dt>data</dt><dd> Output a JSON array of data URIs</dd>
            <dt>data-utfhack</dt><dd> Output a JSON array of data URIs, in which each character of the base64 is a UTF character hiding two ASCII characters</dd>
        </dl>
    </td>
  </tr><tr>
    <td><code>uri</code></td>
    <td>URL, imageset</td>
    <td>An URL-encoded URI to use as the image source, e.g. <code>/v1/images/raw/flags%3Agb,http%3A%2F%2Fexample.com%2Fimage%2Ejpg</code>. Scheme part of the URI may be omitted if the <code>source</code> property is provided. Alternative to the <code>imageset</code> property.</td>
  </tr><tr>
    <td><code>source</code></td>
    <td>Querystring</td>
    <td>
	    Default type of source/scheme in <code>uri</code>s. Custom schemes allow the service to back onto a variety of data sources, including sets of images that may be built into the service itself. Supported source types:
	    <dl>
	    	<dt>http</dt><dd> HTTP URLs of source images anywhere on the public web</dd>
	    	<dt>https</dt><dd> HTTPS URLs of source images anywhere on the public web</dd>
	    	<dt>flags</dt><dd> ISO 3166 two letter country codes</dd>
	    	<dt>heads</dt><dd> Slugified names of known FT columnists and others for whom we show headshots</dd>
            <dt>icons</dt><dd> Identifiers for icons within standard FT icon set</dd>
            <dt>social</dt><dd> Identifiers for button images representing common social platforms (e.g. facebook, twitter, linkedin, reddit, tumblr, digg, weibo, douban, googleplus)</dd>
	    	<dt>ftcms</dt><dd> UUID of image in Content API *(Problem: can't currently look up an image)*</dd>
		</dl>
	</td>
  </tr><tr>
    <td><code>imageset</code></td>
    <td>Querystring</td>
    <td>A JSON array of objects in which each object has a required <code>uri</code> property along with (optionally) other imageset-compatible properties defined on this API. Any property not defined for an individual image will fall back to the value defined for the request (and then to the default, if not defined on the query string). The <code>uri</code> property may include URI without a scheme if <code>source</code> property is provided.</td>
  </tr><tr>
    <td><code>width</code></td>
    <td>Querystring, imageset</td>
    <td>Width of desired output image in pixels.  Defaults to a width that maintains the aspect of the image, or the width of the source image if `height` is also not set.</td>
  </tr><tr>
    <td><code>height</code></td>
    <td>Querystring, imageset</td>
    <td>Height of desired output image in pixels.  Defaults to a height that maintains the aspect of the image, or the height of the source image if `width` is also not set.</td>
  </tr><tr>
    <td><code>fit</code></td>
    <td>Querystring, imageset</td>
    <td>
    	Type of transform to apply if the source aspect ratio does not perfectly match the target (subset of rules defined by CSS <a href="http://www.w3.org/TR/css3-images/#the-object-fit">`object-fit` property</a>):
    	<dl>
            <dt>cover (default)</dt><dd> The image should be scaled to be as small as possible while ensuring both its dimensions are greater than or equal to the corresponding dimensions of the frame, and any cropping should be taken equally from both ends of the overflowing dimension</dd>
            <dt>contain</dt><dd> The image should be scaled to be as large as possible while ensuring both its dimensions are less than or equal to the corresponding dimensions of the frame. The frame should then be collapsed to match the aspect ratio of the image.</dd>
    		<dt>contain-padded</dt><dd> The image should be scaled to be as large as possible while ensuring both its dimensions are less than or equal to the corresponding dimensions of the frame. Any space in the frame not occupied by the image should be transparent or filled with `bgcolor`.</dd>
    		<dt>fill</dt><dd> The image should be scaled so that both its dimensions exactly match the frame, regardless of its original aspect ratio.</dd>
    	</dl>
    </td>
  </tr><tr>
    <td><code>bgcolor</code></td>
    <td>Querystring, imageset</td>
    <td>Background colour to apply to regions of images that would be transparent where the output image format does not support transparency (ie, JPEG).  Specified as six-character RGB hex code, eg `00ff00`</td>
  </tr><tr>
    <td><code>format</code></td>
    <td>Querystring, imageset</td>
    <td>
    	Desired output format.
    	<dl>
    		<dt>auto (default)</dt><dd>Use <code>Accept</code> header from request to determine the best output format</dd>
    		<dt>jpg</dt><dd> Format images as jpg</dd>
    		<dt>png</dt><dd> Format images as png</dd>
    		<dt>webp</dt><dd> Format images as webp</dd>
    		<dt>svg</dt><dd> Format images as SVG (only available if source image is SVG)</dd>
    	</dl>
    </td>
  </tr><tr>
    <td><code>quality</code></td>
    <td>Querystring, imageset</td>
    <td>Compression level for lossy encoding.  May be set to 'lowest', 'low', 'medium', 'high', 'highest', or 'lossless'. If lossless is not supported by chosen image format (JPG), the highest level will be used instead.  Default is medium.</td>
  </tr>
</table>

The service stores cached copies of images as retrieved from origin.  Cached copies of transformed images are cached by CDN.

## Examples

Fetch flags of European countries at 40x30px as a CSS sprite

    /v1/images/css-sprite/gb,fr,de,be,es,fi,hu,it,je,lt,no,pl,se?format=png&width=40&height=30&source=flags

Get a headshot of John Gapper at 50px wide in auto-detected image format:

	/v1/images/raw/heads:john.gapper?width=50

Download a set of images for the web app based on their UUIDs, ready-encoded using UTF-hack:

	/v1/images/data-utfhack?source=ftcms&imageset=[{"uri":"48c9d290-874b-11e3-baa7-0800200c9a66"},{"uri":"48c9d291-874b-11e3-baa7-0800200c9a66"}]&format=jpg

Download two flags, John Gapper's headshot, and an FTCMS image, all as data URIs in JSON array in one request

    /v1/images/data?fit=cover&source=flags&imageset=[{"uri":"gb"},{"uri":"fr"},{"uri":"heads:john.gapper","width":50,"height":50},{"uri":"ftcms:48c9d290-874b-11e3-baa7-0800200c9a66"}]

## Restricting use to FT sites

Image requests must contain a `Referer` header with a recognised FT site hostname.

## Unresolved questions

* Art direction!
* Invocation in markup

# Responsive images service

Image resizing proxy for FT responsive images.

**Placeholder repo to speculatively define the service that we might build.  This service doesn't actually work**

## API reference

API endpoints as follows:

### GET /v1/images/

Fetch an image or set of images, format and serve them.

<table class='o-techdocs-table'>
  <tr>
    <th>Param</th><th>Where</th><th>Description</th>
  </tr><tr>
    <td><code>sourcetype</code></td>
    <td>Querystring</td>
    <td>
	    Type of source.  <code>imageset</code> is a list of URL encoded, comma delimited identifiers that make sense in the <code>sourcetype</code> namespace.  Allows the service to back onto a variety of data sources, including sets of images that may be built into the service itself.  Suggested source types:
	    <ul>
	    	<li><strong>http</strong>: HTTP URLs of source images anywhere on the public web</li>
	    	<li><strong>https</strong>: HTTPs URLs of source images anywhere on the public web</li>
	    	<li><strong>flags</strong>: ISO 3166 two letter country codes</li>
	    	<li><strong>heads</strong>: Slugified names of known FT columnists and others for whom we show headshots</li>
	    	<li><strong>icons</strong>: Identifiers for icons within standard FT icon set</li>
	    	<li><strong>ftcms</strong>: UUID of image in Content API *(Problem: can't currently look up an image)*</li>
		</ul>
	</td>
  </tr><tr>
    <td><code>id</code></td>
    <td>Querystring</td>
    <td>A comma-separated list of URL encoded identifers that make sense in the context of the `sourcetype`.  Alternative to `imageset`.</td>
  </tr><tr>
    <td><code>imageset</code></td>
    <td>Querystring</td>
    <td>A JSON array of JSON objects in which each object has a required `id` property along with (optionally) any of the other properties defined on this API except `opmode` (which must have a single value that applies to the whole request).  Any property not defined for an individual image will revert to the value defined for the request (and then to the default, if not defined on the query string).  Alternative to `id`.</td>
  </tr><tr>
    <td><code>width</code></td>
    <td>Querystring</td>
    <td>Width of desired output image in pixels.  Defaults to a width that maintains the aspect of the image, or the width of the source image if `height` is also not set.</td>
  </tr><tr>
    <td><code>height</code></td>
    <td>Querystring</td>
    <td>Height of desired output image in pixels.  Defaults to a height that maintains the aspect of the image, or the height of the source image if `width` is also not set.</td>
  </tr><tr>
    <td><code>cropmode</code></td>
    <td>Querystring</td>
    <td>
    	Type of transform to apply if the source aspect ratio does not perfectly match the target (subset of rules defined by CSS <code>background-size</code> property):
    	<ul>
    		<li><strong>cover</strong> (default): The image should be scaled to be as small as possible while ensuring both its dimensions are greater than or equal to the corresponding dimensions of the frame, and any cropping should be taken equally from both ends of the overflowing dimension</li>
    		<li><strong>fit</strong>: The image should be scaled to be as large as possible while ensuring both its dimensions are less than or equal to the corresponding dimensions of the frame.  Any space in the frame not occupied by the image should be transparent.</li>
    		<li><strong>stretch</strong>: The image should be scaled so that both its dimensions exactly match the frame, regardless of its original aspect ratio.</li>
    	</ul>
    </td>
  </tr><tr>
    <td><code>opformat</code></td>
    <td>Querystring</td>
    <td>
    	Desired output format.
    	<ul>
    		<li><strong>auto</strong> (default): Use <code>Accept</code> header from request to determine the best output format</li>
    		<li><strong>jpg</strong>: Format images as jpg</li>
    		<li><strong>png</strong>: Format images as png</li>
    		<li><strong>webp</strong>: Format images as webp</li>
    		<li><strong>svg</strong>: Format images as SVG (only available if source image is SVG)</li>
    	</ul>
    </td>
  </tr><tr>
    <td><code>compression</code></td>
    <td>Querystring</td>
    <td>Compression level for lossy encoding  (1-100). Leave blank or set to 0 for lossless (if lossless is not supported by chosen image format, the lowest possible compression level will be used instead)</td>
  </tr><tr>
    <td><code>opmode</code></td>
    <td>Querystring</td>
    <td>
    	How output should be packaged.
    	<ul>
    		<li><strong>raw</strong> (default): Output raw image data.  Requests for multiple images must will be output as a sprite in which the images are tiled in a single horizontal line. Per-image `opformat` override not available for this output mode (because you can't output an image that is part PNG, part JPEG!)</li>
    		<li><strong>css-sprite</strong>: Output a CSS spritesheet containing the images as data URIs attached to classes with names matching the input identifiers (as closely as possible)</li>
    		<li><strong>data</strong>: Output a JSON array of data URIs</li>
    		<li><strong>data-utfhack</strong>: Output a JSON array of data URIs, in which each character of the base64 is a UTF character hiding two ASCII characters</li>
    	</ul>
    </td>
  </tr>
</table>

If both `imageset` and `id` are defined, `id` is ignored.

The service stores cached copies of images as retrieved from origin.  Cached copies of transformed images are cached by CDN.

## Examples

Fetch flags of European countries at 40x30px as a CSS sprite

    http://<host>/v1/images?opmode=css-sprite&opformat=png&width=40&height=30&sourcetype=flags&id=gb,fr,de,be,es,fi,hu,it,je,lt,no,pl,se

Get a headshot of John Gapper at 50px wide in auto-detected image format:

	http://<host>/v1/images?sourcetype=heads&id=john.gapper&width=50

Download a set of images for the web app based on their UUIDs, ready-encoded using UTF-hack:

	http://<host>/v1/images?sourcetype=ftcms&id=48c9d290-874b-11e3-baa7-0800200c9a66,48c9d291-874b-11e3-baa7-0800200c9a66&opformat=jpg&opmode=data-utfhack

Download two flags, John Gapper's headshot, and an FTCMS image, all as data URIs in JSON array in one request

    http://<host>/v1/images?opmode=datacropmode=cover&imageset=[{"id":"gb"},{"id":"fr"},{"sourcetype":"heads","id":"john.gapper","width":50,"height":50},{"sourcetype":"ftcms","id":"48c9d290-874b-11e3-baa7-0800200c9a66"}]

## Restricting use to FT sites

Image requests must contain a `Referer` header with a recognised FT site hostname.

## Unresolved questions

* Art direction!
* Invocation in markup

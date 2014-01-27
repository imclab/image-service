# Responsive images service

Image resizing proxy for FT responsive images.

**Placeholder repo to speculatively define the service that we might build.  This service doesn't actually work**

## API reference

API endpoints as follows:

### GET /v1/`:sourcetype`/`:sourcepaths`

Fetch an image or set of images, format and serve them.

<table class='o-techdocs-table'>
  <tr>
    <th>Param</th><th>Where</th><th>Description</th>
  </tr><tr>
    <td><code>:sourcetype</code></td>
    <td>URL</td>
    <td>
	    Type of source.  <code>sourcepath</code> is a list of URL encoded, comma delimited identifiers that make sense in this namespace.Allows the service to back onto a variety of data sources, including sets of images that may be built into the service itself.  Suggested source types:
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
    <td><code>:sourcepaths</code>*</td>
    <td>URL</td>
    <td>Comma-separated list of URL encoded identifers that make sense in the context of the sourcetype</td>
  </tr><tr>
    <td><code>w</code></td>
    <td>Querystring</td>
    <td>Width of desired output image in pixels (if multiple images are being requested, this is the width of each one).  Defaults to the width of the first source image.</td>
  </tr><tr>
    <td><code>h</code></td>
    <td>Querystring</td>
    <td>Height of desired output image in pixels (if multiple images are being requested, this is the height of each one).  Defaults to the height of the first source image.</td>
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
    		<li><strong>raw</strong> (default): Output raw image data.  Requests for multiple images will be output as a sprite in which the images are simple tiled in a single horizontal line.</li>
    		<li><strong>css-sprite</strong>: Output a CSS spritesheet containing the images as data URIs attached to classes with names matching the input identifiers (as closely as possible)</li>
    		<li><strong>data</strong>: Output a JSON array of data URIs</li>
    		<li><strong>data-utfhack</strong>: Output a JSON array of data URIs, in which each character of the base64 is a UTF character hiding two ASCII characters</li>
    	</ul>
    </td>
  </tr>
</table>

## Examples

Fetch flags of European countries at 40x30px as a CSS sprite

    http://<host>/flags/gb,fr,de,be,es,fi,hu,it,je,lt,no,pl,se?opmode=css-sprite&opformat=png&w=40&h=30

Get a headshot of John Gapper at 50x50 in auto-detected image format:

	http://<host>/heads/john.gapper

Download a set of images for the web app based on their UUIDs, ready-encoded using UTF-hack:

	http://<host>ftcms/48c9d290-874b-11e3-baa7-0800200c9a66,48c9d291-874b-11e3-baa7-0800200c9a66?opformat=jpg&opmode=data-utfhack

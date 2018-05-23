# passio

## Single posts

Each video (standard & extended) gets its own WP post which uses custom fields to pull in pertinant data:

- `BC_ID_exam`: Brightcove video ID for examination video
- `BC_ID_presentation`: Brightcove video ID for presentation video
- `BC_ID_ext`: Brightcove video ID for extended video
- `BC_ID_stand`: Brightcove video ID for standard video
- `BC_TOC_exam`: URL for TOC for examination video ([example](http://brightcove.vo.llnwd.net/v1/unsecured/media/4741948344001/201803/1026/4741948344001_d62b91d1-ae6e-436e-be55-ca561ece5818.vtt?pubId=4741948344001&videoId=5755132843001))
- `BC_TOC_presentation`: URL for TOC for presentation video ([example](http://brightcove.vo.llnwd.net/v1/unsecured/media/4741948344001/201803/1026/4741948344001_d62b91d1-ae6e-436e-be55-ca561ece5818.vtt?pubId=4741948344001&videoId=5755132843001))
- `BC_TOC_ext`: URL for TOC for extended video ([example](http://brightcove.vo.llnwd.net/v1/unsecured/media/4741948344001/201803/1026/4741948344001_d62b91d1-ae6e-436e-be55-ca561ece5818.vtt?pubId=4741948344001&videoId=5755132843001))
- `BC_TOC_stand`: URL for TOC for standard video ([example](http://brightcove.vo.llnwd.net/v1/unsecured/media/4741948344001/201803/1026/4741948344001_d62b91d1-ae6e-436e-be55-ca561ece5818.vtt?pubId=4741948344001&videoId=5755132843001))
- `prezi_src`: URL for prezi presentation 
- `footer`: HTML for extra footer to show, see [post](http://passioeducation.com/submuscular-ulnar-nerve-transposition/)

## Wordpress loop

The main WP loop loads a cached version of the Brightcove database; this was done at the time because of the limited use of a previous API; the [new API](https://brightcovelearning.github.io/Brightcove-API-References/cms-api/v1/doc/index.html#api-videoGroup-Get_Videos) has now been implemented and I'm not sure if we can just use it to directly generate the loop data. However I'm keeping things as they are now in order to minimize development.

The Brightcove database is cached by visiting [this](http://passioeducation.com/download-database/) site. It uses the [download_brightcove()](https://github.com/ConstantinoSchillebeeckx/passio/blob/master/wp/wp-content/themes/PASSIO/functions.php#L2613) function and stores the results in the file [BC_DB.json](https://github.com/ConstantinoSchillebeeckx/passio/blob/master/wp/BC_DB.json).

This cached version is then used with [loop.php](https://github.com/ConstantinoSchillebeeckx/passio/blob/master/wp/wp-content/themes/PASSIO/loop.php) to generate a post snippet that looks like:

![snippet](snippet.png)

Notice how the `long description` of the video (as stored on Brightcove) is being used to populate the data.

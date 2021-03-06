<?php
  // define the current collection class
  $collection_class = ('collection' .$requested_collection_id);
  echo head(array('bodyclass'=>$collection_class)); 

  // manually set the encoding
  header('Content-Type: text/html; charset=utf-8');

  // set the current collection title as the <title> value for the page
  $this->headTitle()->prepend( mysql_result($collection_title, 0) );
  echo $this->headTitle(); 
?>

<script>
  // use jQuery to add classes to body
  $("body").addClass("collections show");
</script>

<style>
/* pagination styles */
.pagination-container {
  overflow: hidden;
}
.pagination-outer {
  position: relative;
  left: 50%;
  float: left;
}
.pagination-inner {
  position: relative;
  left: -50%;
  float: left;
}
.page {
  display: inline-block;
  padding: 2px 9px;
  background: #faf9f9;
  border: 1px solid #d3d3d3;
  color: #000;
}
.pagination-container .page {
  margin: 2px;
}
.current-page {
  color: #f6a947;
}
.pagination-container a {
  text-decoration: none;
}
.page:hover {
  background: #d1d0d0;
}
.current-page:hover {
  color: #ff8f00;
  background: #d1d0d0;
}

@media (max-width: 767px) {
	.main {
  	padding-left: 0;
	}
	div.collectionTitle {
	  width:100%;
	  padding-left:15px;
	}
	.collectionTitle h1 {
	  text-align:left;
	}
	div.collectionDesc {
		margin-top:0;
		padding-right:10px;
	}
	div#pageshavebeen {
		text-align:left;
	}
}
</style>

<div class="section-title">
  <div class="header-gradient">
    <div class="collectionTitle">
      <h1><?php echo mysql_result($collection_title, 0); ?></h1>
    </div>
    <div class="main">
      <div class="container-fluid">
        <div class="row-fluid">
          <div class="collectionDesc">
            <?php echo mysql_result($collection_description, 0); ?>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>

<div class="collectionTitle" id="pageshavebeen">
  <strong><?php echo $total_files_transcribed; ?></strong> of 
  <strong><?php echo $total_files; ?></strong> pages transcribed
</div>
<div class="main">
  <div class="container-fluid">
    <div class="row-fluid">
      <div id="progressBar">
        <div id="collectionProgress" class="progress">
          <div class="progress-bar"
            aria-valuenow="<?php echo $total_percent_complete; ?>"
            aria-valuemin="0"
            aria-valuemax="100"
            style="width:<?php echo $total_percent_complete; ?>%"
          >
            <span class="sr-only">
              <?php echo $total_percent_complete; ?>% Completed
            </span>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>

<?php include 'pagination_template.php' ?>

<div id="columns" class="container">
  <div class="masonryrow">

    <?php
    while ($row = mysql_fetch_array($query_response)) {
      // fetch the metadata for each row of the query response
      $item_id = $row["id"];
      $item_title = $row["oettitle"];
      $item_author = $row["oetauthor"];
      $item_file_name = $row["filename"];
      $item_date = $row["oetdate"];
      $item_box_folder = $row["oetboxfolder"];
      $item_percent_complete = $row["oetpercentcomplete"];
      $path_to_item = $application_root."items/show/".$item_id; 
      $path_to_image = "http://transcribe.library.yale.edu/"
        ."projects/files/thumbnails/"
        .$item_file_name; 
      ?>
      
      <figure>
        <div class="masonrywell">
          <div class="thumbholder"> 
            <a href=" <?php echo $path_to_item; ?> ">
              <img src=" <?php echo $path_to_image; ?> "
                    alt=" <?php echo $item_title; ?> " 
              />
              <div class="hoverEdit">
                <span class="glyphicon glyphicon-pencil"></span>
              </div>
              <div class="hoverMeta">
                <span class="glyphicon glyphicon-info-sign"></span>
              </div>
            </a>
          </div>
          <div class = "progress item-progress">
            <div class="progress-bar" 
                  role="progressbar"
                  aria-valuenow="<?php echo $item_percent_complete; ?>"
                  aria-valuemin="0"
                  aria-valuemax="100"
                  style="width: <?php echo $item_percent_complete; ?>%;">
              <span class="sr-only">
                <?php echo $item_percent_complete;?>% Transcribed
              </span>
            </div>
          </div>
          <figcaption>
            <a href=" <?php echo $path_to_item; ?> ">
              <h3><?php echo $item_title; ?></h3>
            </a>
            <?php $item_metadata = array($item_author, $item_date, $item_box_folder);
            foreach ($item_metadata as &$item_m) {
              if (isset($item_m)) {
                if ($item_m != 'undated') {
                  echo $item_m . '<br>';
                }
              }
            }; ?>
          </figcaption> 
        </div>
      </figure>   
    <?php } ?>
  </div>
</div> 

<?php fire_plugin_hook('public_collections_show', 
  array('view' => $this, 'collection' => $requested_collection_id)); ?>
<?php echo foot(); ?>

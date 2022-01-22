<?php
global $CFG;
/*global $USER;
require_once('/var/www/html/moodle/config.php' );*/

require_once($CFG->dirroot. '/theme/edumy/ccn/block_handler/ccn_block_handler.php');
class block_cocoon_gallery_updated extends block_base
{
    // Declare first
    public function init()
    {
      $this->title = get_string('cocoon_gallery_updated', 'block_cocoon_gallery_updated');
    }

    // Declare secondss
    public function specialization()
    {
        // $this->title = isset($this->config->title) ? format_string($this->config->title) : '';
        global $CFG;
        include($CFG->dirroot . '/theme/edumy/ccn/block_handler/specialization.php');
    }


//////
/*
return $DB->get_records_sql ( "SELECT ra.id, ra.userid, ra.contextid, ra.roleid, r.name, r.shortname
FROM {role_assignments} ra
JOIN {context} c ON ra.contextid = c.id
JOIN {role} r ON ra.roleid = r.id
WHERE ra.userid = ?
ORDER BY contextlevel DESC, contextid ASC, r.sortorder ASC";
$roleassignments = $DB->get_records_sql($sql, array($USER->id));

$user_roles = array();
foreach($roleassignments as $k => $v){
$user_roles[$v->roleid] = $v->name;
}

*/



////////start code


public function get_content(){ // render
  // require_once($CFG->libdir . '/filelib.php');
  global $USER, $COURSE, $DB;

  
  if (!isset($_GET["id"])) {
    return $this->content;
  }

  $this->content         =  new stdClass;
  if(!is_null($this->config)){
      $this->title  = '';
      $this->content->title = $this->config->title;
  } else {
    $this->content->title = 'Our Gallery';
  }
  
  if(!is_null($this->config)){
    $this->content->subtitle = $this->config->subtitle;
  } else {
    $this->content->subtitle = 'Cum doctus civibus efficiantur in imperdiet deterruisset.';
  }
  $userid = $USER->id;
  $context = context_course::instance($COURSE->id);
  $roles = get_user_roles($context, $USER->id, true);
  $role = key($roles);
  $rolename = $roles[$role]->shortname;
  // is admin 
  $admins = get_admins();
  $isadmin = false;
  foreach($admins as $admin) {
      if ($USER->id == $admin->id) {
          $isadmin = true;
          break;
      }
  }
  $this->content->image = '
       <div class="">
          <div id="galery">
              
          </div>
        </div>';

  $javascript = '
        <script>
          getImg()
          function getImg(){
            $("#galery").empty();
              $.ajax({
                url: "/plugin_gallery/?f=json_all",
                method: "GET",
                dataType: "json",
                async: false,
                crossDomain: "true",
                success: function(data, status) {
                    console.log("Status: "+status);

                    Array.from(data["images"]).forEach((img, index) => {

                      let div_temp = $("<div />", {
                        class: "gallery_item col-3 d-inline-block",
                        id: img["id"]+"_div"
                      })
                      div_temp.appendTo($("#galery"));

                      let img_temp = $("<img />", {
                        id: img["id"],
                        src: img["data"],
                        class: "img-fluid img-circle-rounded w100"
                      });
                      img_temp.appendTo(div_temp);
                      
                      $("#"+img["id"]).after(`
                      <div class="gallery_overlay"><a class="ccn-icon popup-img" href=${img["data"]} ><span class="flaticon-zoom-in"></span></div>
                      `)
                    })

                }
              });
          }

          function getBase64(file) {
            return new Promise((resolve, reject) => {
              const reader = new FileReader();
              reader.readAsDataURL(file);
              reader.onload = () => resolve(reader.result);
              reader.onerror = error => reject(error);
            });
          }



           function send_backend(files) {
            return new Promise((resolve, reject) => {
              Array.from(files).forEach((file, index) => {  
                getBase64(file).then(
                  data => {
                    object = [{
                      data: data,
                      id: (new Date()).getTime(),
                      courseid: '.$_GET["id"].',
                      userid: '.$userid.'
                    }]
                    $.ajax({
                      type: "POST",
                      url: "/plugin_gallery/",
                      data:  JSON.stringify(object),
                      contentType: "application/json;",
                      async: false,
                      success: function (data) {
                        console.log(data)
                        if (index === Array.from(files).length - 1){ 
                          resolve("ok")
                        }
                      }
                    })

                  }
                );
              })
            })
            
          }
          
    
    
        function send_img(){
            var files = document.querySelector(`input[type="file"]`).files
            send_backend(files).then(data => {
              getImg()
              alert("success")
            })
        }


        $( "#submit" ).click(function() {
          send_img()
        });

        
        </script>';
    if ($rolename == 'manager' || $isadmin == true) {
      $form = '<div class="col-lg-12 ">
      <div>
        <label for="avatar">Choose a profile picture:</label>
        <input type="file" name="img[]" multiple
              accept="image/png, image/jpeg" id="#files">
        <button id="submit" >Submit</button>
      </div>
    </div>';
    }else {
      $form = '';
    }


  $this->content->text = '
    <section class="about-section pb0">
      <div class="container">';
      $this->content->text .='
      <div class="row">
      '.$form.'
      </div>
    <style>
      .dndupload-arrow-2 {
        background: url("/theme/image.php/edumy/theme/1638926165/fp/dnd_arrow") center no-repeat;
        width: 10%;
        height: 80px;
        position: absolute;
        left:45%;
      }
      .dropzone {
        min-height: 190px;
      }

      
      .ccn_gallery_col_medium {
        -ms-flex: 0 0 33.33333333% !important;
        flex: 0 0 33.33333333% !important;
        max-width: 33.33333333% !important;
      }
    
    </style>
      <section class="about-section pb10">
              <div class="container">
                <div class="row">
                  <div class="col-lg-6 offset-lg-3">
                    <div class="main-title text-center">
                      <h3 class="mt0">'.format_text($this->content->title, FORMAT_HTML, array('filter' => true)).'</h3>
                      <p>'.format_text($this->content->subtitle, FORMAT_HTML, array('filter' => true)).'</p>
                    </div>
                  </div>
                </div>
                <div class="row d-flex justify-content-center">
                  '. $this->content->image .'
                </div>
              </div>
            </section>
        </div>
      </section>
      '.$javascript;
      
  return $this->content;
}


///////finish code



    /**
     * Allow multiple instances in a single course?
     *
     * @return bool True if multiple instances are allowed, false otherwise.
     */
    public function instance_allow_multiple() {
        return true;
    }

    /**
     * Enables global configuration of the block in settings.php.
     *
     * @return bool True if the global configuration is enabled.
     */
    function has_config() {
        return true;
    }

    /**
     * Sets the applicable formats for the block.
     *
     * @return string[] Array of pages and permissions.
     */
     function applicable_formats() {
       $ccnBlockHandler = new ccnBlockHandler();
       return $ccnBlockHandler->ccnGetBlockApplicability(array('all'));
     }
     public function html_attributes() {
       global $CFG;
       $attributes = parent::html_attributes();
       include($CFG->dirroot . '/theme/edumy/ccn/block_handler/attributes.php');
       return $attributes;
     }

}

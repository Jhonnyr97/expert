<?php
global $CFG;
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

////////start code

// /COMIENZO COMENTARIO CODIGO DE MAURO/
public function get_content(){ // renderizar la info
  // require_once($CFG->libdir . '/filelib.php');
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

  if(!empty($this->config->columns)){
    if($this->config->columns == 4) { //6
      $columns = 'col-sm-6 col-md-6 col-lg-2 ccn_gallery_col_6';
    } elseif($this->config->columns == 3) { //4
      $columns = 'col-sm-6 col-md-6 col-lg-3 ccn_gallery_col_4';
    } elseif($this->config->columns == 2) { //3
      $columns = 'col-sm-6 col-md-4 col-lg-4 ccn_gallery_col_3';
    } elseif($this->config->columns == 1) { //2
      $columns = 'col-sm-6 col-md-6 col-lg-6 ccn_gallery_col_2';
    } else { //1
      $columns = 'col-sm-12 col-md-12 col-lg-12';
    }
  } else {
    $columns = 'col-sm-12 col-md-12 col-lg-4 ccn_gallery_col_medium';
  }

  $fs = get_file_storage();
  $files = $fs->get_area_files($this->context->id, 'block_cocoon_gallery_updated', 'content');
  $this->content->image = '';
  if (!is_null($this->config)) {
    foreach ($files as $file) {
        $filename = $file->get_filename();
        if ($filename <> '.') {
            $url = moodle_url::make_pluginfile_url($file->get_contextid(), $file->get_component(), $file->get_filearea(), null, $file->get_filepath(), $filename);
            $this->content->image .= '
            <div class="'.$columns.'">
               <div class="gallery_item">
               <img class="img-fluid img-circle-rounded w100" src="' . $url . '" alt="' . $filename . '">
                <div class="gallery_overlay">
                  
                 <a class="ccn-icon popup-img" href="' . $url . '"><span class="flaticon-zoom-in"></span></a>
                </div>
              </div>
            </div>';
        }
    }
  } else {
     for ($i=0; $i < 3; $i++) { 
       $this->content->image .= '
         <div class="'.$columns.'">
           <div class="gallery_item">
             <img class="img-fluid img-circle-rounded w100" src="https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcT3bBWxJLrkz7GyJn-X-5iM03kIcKTezER2tA&usqp=CAU" alt="Img '. $i.'">
               <div class="gallery_overlay">
                 <a class="ccn-icon popup-img" href="https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcT3bBWxJLrkz7GyJn-X-5iM03kIcKTezER2tA&usqp=CAU"><span class="flaticon-zoom-in"></span></a>
               </div>
             </div>
           </div>';
     }
  } 
  
  $this->content->text = '
    <section class="about-section pb0">
      <div class="container">';
      if($this->content->title || $this->content->subtitle){
        $this->content->text .='
        <div class="row">
            <div class="col-lg-12 ">
            <form name="" action="/course/view.php" method="POST" 
            class="modal-content view_render_updated" id="view-render_'.$this->context->id.'">
            <div class="modal-header">
              <h5 class="modal-title">Edit [Cocoon] Gallery</h5>
            </div>
            <div class="modal-body">
              <div class="form-group row">
                <label for="id_config_title" class="col-sm-5 col-form-label text-right">Title: </label>
                <div class="col-sm-5">
                  <input type="text" class="form-control" id="id_config_title" name="config_title" value="'.$this->content->title.'">
                </div>
              </div>
              <div class="form-group row">
                <label for="sub_title" class="col-sm-5 col-form-label text-right">Subtitle: </label>
                <div class="col-sm-5">
                  <input type="text" class="form-control" name="config_subtitle" id="sub_title" value="'.$this->content->subtitle.'">
                  <input type="hidden" name="itemid" id="itemid_'.$this->context->id.'" value="'.$this->context->id.'">
                </div>
              </div>
              <div style="display:none;">
                <input type="hidden" name="id" value="'.$_GET["id"].'">
                <input type="hidden" name="bui_editid" value="'.$this->context->instanceid.'">
                <input name="sesskey" type="hidden" value="'.sesskey().'">
                <input name="_qf__block_cocoon_gallery_updated_edit_form" type="hidden" value="1">
                <input name="mform_isexpanded_id_config_header" type="hidden" value="1">
                <input name="mform_isexpanded_id_config_cocoon_block_settings" type="hidden" value="0">
                <input name="mform_isexpanded_id_whereheader" type="hidden" value="0">
                <input name="mform_isexpanded_id_onthispage" type="hidden" value="0">
                <input name="bui_weight" type="hidden" value="5">
                <input name="bui_region" type="hidden" value="below-content">
                <input name="bui_visible" type="hidden" value="1">
                <input name="bui_defaultweight" type="hidden" value="3">
                <input name="bui_defaultregion" type="hidden" value="side-pre">
                <input name="bui_pagetypepattern" type="hidden" value="course-view-*">
                <input name="config_image" type="hidden" value="'.$this->context->id.'">
              </div>
              <div id="myDropzone_'.$this->context->id.'" class="dropzone">
                <div class="dz-message needsclick">
                  <div class="subtitle">To upload files, drag and drop them here.</div>
                  <h3 class="dropzone-custom-title">
                    <div class="dndupload-arrow-2"></div>  
                  </h3>
                </div>
              </div>
            </div>
            <div class="modal-footer">
              <button type="submit" class="btn btn-primary">Save changes</button>
            </div>
          </form>
            </div>
          </div>
          <script>
            function renderBlock() {
              let valor = $("input[name=\'edit\']").val();
              // console.log("valor", valor);
              if (valor == "off") {
                $(".view_render_updated").css("display","block");
              } else {
                $(".view_render_updated").css("display","none");
              }
            }

            function guardarCambios (context_id) {
              let datos = $("#view-render_"+context_id).serialize();
              let boton = $("#btn-guardar_"+context_id);
              boton.prop("disabled",true);
              $.ajax({
                type:"POST",
                url:"/course/viewupdated.php",
                data: datos,
                success: function (response) {
                  console.log(response);
                }
              });
              boton.prop("disabled",false);
            
            }



            $("#myDropzone_'.$this->context->id.'").dropzone({            
              paramName: "repo_upload_file", // The name that will be used to transfer the file
              maxFilesize: 5, // MB
              maxFile: 12,
              dictDefaultMessage:"",
              dictRemoveFile: "Remove Image",
              url: "/repository/repository_ajax.php?action=upload",
              acceptedFiles: "image/*",
              addRemoveLinks: true,
              thumbnailWidth: 120,
              init: function () {
                let myDropzone = this;
                $.ajax({
                  type:"POST",
                  url:"/repository/draftfiles_ajax.php?action=list",
                  data: {
                    "sesskey": M.cfg.sesskey,
                    "filepath":  "/",
                    "itemid":  $("#itemid_'.$this->context->id.'").val(),
                  },
                  success: function (response) {
                    let arreglo = response.list
                    for(let i=0; i<arreglo.length; i++) {
                      let el = {name: arreglo[i].filename, url:arreglo[i].url, size: arreglo[i].size};
                      myDropzone.emit("addedfile", el);
                      myDropzone.emit("thumbnail", el, el.url);

                      el.previewElement.classList.add("dz-success");
                      el.previewElement.classList.add("dz-complete");
                   
                      myDropzone.files.push(el);
                    }

                  }
                });
               
                this.on("sending", function(file, xhr, formData) {
                  formData.append("sesskey", M.cfg.sesskey);
                  formData.append("savepath", "/");
                  formData.append("author", "Admin YOUniversity");
                  formData.append("accepted_types", "[.png, .jpg, .gif]");
                  formData.append("title",file.name);
                  formData.append("repo_id","5");
                  formData.append("itemid", $("#itemid_'.$this->context->id.'").val());
               
                });
                this.on("addedfile", function(file) {
                });

                this.on("removedfile", function (file) {
                  let myDropzone = this;
                  let datos = {
                    "sesskey": M.cfg.sesskey,
                    "filepath":  "/",
                    "filename": file.name,
                    "itemid":  $("#itemid_'.$this->context->id.'").val(),
                  }
                
                  $.ajax({
                    type:"POST",
                    url:"/repository/draftfiles_ajax.php?action=delete",
                    data: datos,
                    success: function (response) {
                      if (response != false) {
                        
                      }
                    }
                  });
                });

              }
            });
            
          renderBlock();
          // console.log("Iniciando...");
        
        </script>
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
        
        </style>';
      }
      $this->content->text .='<section class="about-section pb10">
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
      </section>';
      
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

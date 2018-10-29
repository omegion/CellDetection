@extends('layouts.app')

@section('title', 'Blood Cell Type Detection')

@section('content')

<section class="bd-index-fullscreen hero is-fullheight is-light">
    <div class="hero-head">
      <div class="container">
        <div class="tabs is-centered">
          <ul>
            <li><a>Blood Cell Type Detection</a></li>
          </ul>
        </div>
      </div>
    </div>
    <div class="hero-body">
      <div class="container">
          <div class="columns">
              <div class="column is-12 has-text-centered">
                <b-upload v-if="!showImage" v-model="file"
                    @input="onFileChange"
                    drag-drop>
                    <section class="section">
                        <div class="content has-text-centered">
                            <p>
                                <b-icon
                                    icon="upload"
                                    size="is-large">
                                </b-icon>
                            </p>
                            <p>Drop your files here or click to upload</p>
                        </div>
                    </section>
                </b-upload>
                <div v-if="showImage" class="avatar-wrapper">
                    <img class="profile-pic has-text-centered" :src="image" alt="" />
                    <div class="upload-button"></div>
                </div>
                <div v-if="showImage">
                    <a class="button is-primary" :href="image" target="blank">Download</a>
                    <button class="button is-grey" @click="showImage = false">Again</button>
                </div>
                <b-loading :is-full-page="true" :active.sync="isLoading" :can-cancel="false"></b-loading>
              </div>
          </div>
      </div>
    </div>
  
    <div class="hero-foot">
      <div class="container">
        <div class="tabs is-centered">
          <ul>
            <li><a href="https://github.com/omegion/blood-cell-classifier-tensorflow" target="blank">GitHub Repository</a></li>
          </ul>
        </div>
      </div>
    </div>
  </section>
@endsection

@section('scripts')
<script type="text/javascript">
    var Main = {
            data:{
                file: null,
                image: '',
                isLoading: false,
                showImage: false
            },
            methods: {
                onFileChange(e) {
                    this.createImage(e);
                },
                createImage(file) {
                    let reader = new FileReader();
                    let vm = this;
                    reader.onload = (e) => {
                        vm.image = e.target.result;
                        this.upload(e.target.result)

                    };
                    reader.readAsDataURL(file);

                },
                upload(image) {
                    var that = this;
                    that.isLoading = true;
                    axios.post("/upload", {
                        image: image,
                    }).then(function (response) {
                        that.image = 'storage/images/'+response.data;
                        that.isLoading = false;
                        that.showImage = true;
                        console.log(response.data);
                        console.log(that.image);
                        
                    })
                    .catch(function (error) {
                        that.isLoading = false;
                        console.log(error);
                    });
                },
            }
        };
</script>
@append
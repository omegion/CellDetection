@extends('layouts.app')

@section('title', 'Blood Cell Type Detection')

@section('content')

<section class="bd-index-fullscreen hero is-fullheight is-light">
    <div class="hero-head">
      <div class="container" style="    margin: 16px auto 0px auto;">
        <div class="tabs is-centered">
          <ul>
            <li>
                <h5 class="title is-5">Blood Cell Type Detection</h5>
            </li>
          </ul>
        </div>
      </div>
    </div>
    <div class="hero-body">
      <div class="container" v-cloak>
            <div class="columns">
                <div class="column is-12 has-text-centered">
                    <div v-if="!showImage" >
                        <b-upload v-model="file"
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
                        <div class="block" style="margin-top:20px;">
                            <b-radio v-model="type"
                                native-value="fast">
                                SSD Mobilenet
                            </b-radio>
                            <b-radio v-model="type"
                                native-value="slow">
                                RCNN Resnet
                            </b-radio>
                        </div>
                    </div>
                    
                    <div class="columns">
                        <transition name="slide-fade">
                            <div v-if="showImage" class="column is-7">
                                <div class="card">
                                    <div class="card-image">
                                        <figure class="image is-4by4">
                                        <img :src="image" alt="Placeholder image">
                                        </figure>
                                    </div>
                                    <div class="card-content">
                                        <div class="content">
                                            <a class="button is-primary" :href="image" target="blank">Download</a>
                                            <button class="button is-grey" @click="showImage = false">Analyze Again</button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </transition>
                        <transition name="slide-fade-right">
                            <div v-if="showImage" class="column is-5">
                                <div class="columns is-multiline">
                                    <div class="column is-6" v-for="(crop, index) in crops" :key="index">
                                        <div class="card">
                                            <div class="card-content">
                                                <div class="media">
                                                    <div class="media-left">
                                                        <figure class="image is-48x48" style="    overflow: hidden;">
                                                            <img :src="'storage/images/'+crop.source" alt="Placeholder image">
                                                        </figure>
                                                    </div>
                                                    <div class="media-content">
                                                        <p class="title is-4">@{{ crop.type | capitalize}}</p>
                                                        <p class="subtitle is-6"> 
                                                            <b-tag :type="crop.score | type">
                                                                <span class="has-text-weight-bold">@{{ crop.score }}%</span> Precision
                                                            </b-tag>
                                                        </p>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>                        
                        </transition>
                        
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
                image: 'storage/images/1542573406_RouQx9R3Cy.jpg',
                crops: {!! json_encode($crops) !!},
                type: 'fast',
                isLoading: false,
                showImage: true
            },
            filters: {
                capitalize: function (string) {
                    return string.charAt(0).toUpperCase() + string.slice(1);
                },
                type: function (value) {
                    if (value >= 65) {
                        return 'is-success';
                    } else if(45 >= value <= 64) {
                        return 'is-warning';
                    } else {
                        return ''
                    }
                }
            },
            mounted() {
                this.crops = this.crops.reverse();
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
                        type: that.type
                    }).then(function (response) {
                        that.image = 'storage/images/'+response.data.file;
                        that.crops = response.data.crops.reverse();
                        that.isLoading = false;
                        that.showImage = true;                        
                        console.log(response.data);
                    })
                    .catch(function (error) {
                        that.isLoading = false;
                        console.log(error.response);
                    });
                },
            }
        };
</script>
@append
<template>
  <div>
    <file-pond
        :name="'_media_[' + collection + ']'"
        ref="pond"
        label-idle="Glisser / Déposer des photos ici..."
        :allow-multiple="true"
        accepted-file-types="image/jpeg, image/png"
        @init="handleFilePondInit"
        :imagePreviewHeight="150"
    />

    <el-pagination
        background
        layout="prev, pager, next"
        :total="pages?pages.total:0"
        @prev-click="goPrev"
        @next-click="goNext"
        @current-change="goToPage"
        :hide-on-single-page="true">
    </el-pagination>

    <div class="filepond--list" style="position: relative;">
      <draggable v-model="media" @end="reorderMedia">
        <transition-group>
          <div v-for="file in media" class="filepond--item" style="height:160px;width: 263px !important; position: relative; float: left;" :key="file.id">
            <fieldset class="filepond--file-wrapper">
              <div class="filepond--file">
                <i class="far fa-edit" @click="fuck(file)" data-toggle="modal" :data-target="'#edit-'+id"
                   style="position: absolute;z-index: 103;right: 34px;top: 8px;background-color: #0000008c;padding: 5px;border-radius: 10px;cursor: pointer;"></i>

                <button @click.prevent="detachMedia(file)" class="filepond--file-action-button filepond--action-revert-item-processing"
                        data-align="right">
                  <svg width="26" height="26" viewBox="0 0 26 26" xmlns="http://www.w3.org/2000/svg">
                    <path
                        d="M11.586 13l-2.293 2.293a1 1 0 0 0 1.414 1.414L13 14.414l2.293 2.293a1 1 0 0 0 1.414-1.414L14.414 13l2.293-2.293a1 1 0 0 0-1.414-1.414L13 11.586l-2.293-2.293a1 1 0 0 0-1.414 1.414L11.586 13z"
                        fill="currentColor" fill-rule="nonzero"></path>
                  </svg>
                  <span>Supprimer</span>
                </button>
                <div class="filepond--file-info">
                            <span class="filepond--file-info-main">
                                {{ file.name }}
                            </span>
                  <span class="filepond--file-info-sub">{{ file.size }}</span>
                </div>
                <div class="filepond--image-preview-wrapper">
                  <div class="filepond--image-preview-overlay filepond--image-preview-overlay-success" style="opacity:0.4;">
                    <svg width="500" height="90" viewBox="0 0 500 90" preserveAspectRatio="none">
                      <rect x="0" width="500" height="90" fill="#fff" mask="url(#mask-2)"></rect>
                    </svg>
                  </div>

                  <div class="filepond--image-preview">
                    <div class="filepond--image-clip">
                      <img :src="file.image_media" style="object-fit: cover; max-width: 100%; height: 170px;" alt="">
                    </div>
                  </div>
                </div>
              </div>
            </fieldset>
          </div>
        </transition-group>
      </draggable>

      <div class="clearfix"></div>
    </div>


    <div class="modal fade" :id="'edit-'+id" v-if="typeform === '0'">
      <form @submit.prevent="update()">
        <div class="modal-dialog">
          <div class="modal-content">
            <modal-header text="Modification des informations de l'image"></modal-header>
            <input type="hidden">
            <div class="modal-body">
              <div class="form-group">
                <label>Titre</label>
                <input class="form-control" v-model="proprietes.titre" placeholder="Titre"/>
              </div>
              <div class="form-group">
                <label>Texte alternatif</label>
                <input class="form-control" v-model="proprietes.alt" placeholder="Alt"/>
              </div>

              <div class="form-group">
                <label>Description</label>
                <input class="form-control" v-model="proprietes.description" placeholder="Description"/>
              </div>
            </div>
            <modal-footer></modal-footer>
          </div>
        </div>
      </form>
    </div>

    <div class="modal fade" :id="'edit-'+id" v-else>
      <form @submit.prevent="update()">
        <div class="modal-dialog">
          <div class="modal-content">
            <modal-header text="Modification des informations de l'image"></modal-header>
            <input type="hidden">
            <div class="modal-body">
              <div class="form-group">
                <label>Titre</label>
                <input class="form-control" v-model="proprietes.titre" placeholder="Titre"/>
              </div>
              <div class="form-group">
                <label>Texte alternatif</label>
                <input class="form-control" v-model="proprietes.alt" placeholder="Alt"/>
              </div>
              <div class="form-group">
                <label>Url</label>
                <input type="url" class="form-control" v-model="proprietes.url" placeholder="Url"/>
              </div>
              <div class="form-group">
                <label>Description</label>
                <textarea v-model="proprietes.description" rows="4" class="form-control"></textarea>
              </div>
            </div>
            <modal-footer></modal-footer>
          </div>
        </div>
      </form>
    </div>

  </div>
</template>

<script>
import $http from '../media/axios';
import 'filepond/dist/filepond.min.css';
import 'filepond-plugin-image-preview/dist/filepond-plugin-image-preview.min.css';
import _debounce from 'lodash/debounce';
import vueFilePond from 'vue-filepond';
import draggable from 'vuedraggable';

const FilePond = vueFilePond(
    require('filepond-plugin-file-validate-type')
);

export default {
  components: {
    FilePond,
    draggable
  },
  props: {
    id: {
      type: String,
      required: true,
    },
    collection: {
      type: String,
      default: 'default',
    },
    conversion: {
      type: String,
      default: 'default',
    },
    endpoint: {
      type: String,
      required: true
    },
    typeform: {
      type: String,
      default: '0'
    }

  },
  data() {
    return {
      page: 1,
      media: [],
      pages: {},
      queued: 0,
      proprietes: {titre: "", alt: "", description: ''},
      startDrag: null,


    }
  },
  methods: {
    async fetchMedia() {
      const response = await $http().get(this.endpoint, {
        params: {
          collection: this.collection,
          page: this.page,
        }
      })
      const {data, ...pages} = response.data
      this.media = data
      this.pages = pages
    },
    async detachMedia(file) {
      if (!window.confirm('Are you sure?')) return false;
      await $http().delete(`${this.endpoint}/${file.id}`);
      if (this.media.length === 1 && this.page > 1) {
        this.page--;
      }
      this.fetchMedia();
    },
    goNext() {
      this.page++;
      this.fetchMedia();
    },
    goPrev() {
      this.page--;
      this.fetchMedia();
    },
    goToPage(page) {
      this.page = page;
      this.fetchMedia();
    },
    handleFilePondInit() {
      const $this = this
      this.$refs.pond._pond.setOptions({
        onaddfile(_, info) {
          $this.queued++;
        },
        server: {
          process: {
            url: `${this.endpoint}/${this.collection}`,
            ondata(formData) {
              let token = document.head.querySelector('meta[name="csrf-token"]');
              formData.append('_token', token.content);

              return formData
            },
            onload(file) {
              if (file && JSON.parse(file)) {
                $this.queued--;

                $this.clearQueue();
              }
            }
          },
          fetch: null,
          revert: null,
          restore: null,
          load: null,
        },
      });
    },
    clearQueue() {
      if (0 === this.queued) {
        this.page = 1
        this.fetchMedia()
        this.$refs.pond._pond.removeFiles()
      }
    },
    reorderMedia: function (evt) {
      if (evt.oldIndex !== evt.newIndex) {
        let ids = this.media.map(media => {
          return media.id
        })
        $http().post(this.endpoint, {ids: ids})
      }
    },


    fuck: function (file) {
      this.proprietes = file.custom_properties
      this.proprietes.id = file.id
    },
    async update() {
      let route = this.endpoint.replace('media', 'custom')
      let response = await $http().post(route, {custom: this.proprietes})
    }


  },
  created() {
    this.$nextTick(this.fetchMedia)
    this.clearQueue = _debounce(this.clearQueue.bind(this), 500)
  },

}
</script>

<style lang="scss">
.filepond--item {
  width: calc(20% - .5em) !important;
}

.media-navigation, .media-navigation {
  font-size: 18px !important;
}

.filepond--drop-label {
  border: .3rem #22669b40 dashed;
}
</style>

<template>
    <div>
        <section>
            <!-- v-if duplicates the .sync in order to force instant-search re-render -->
            <el-dialog title="Ajouter un élément" :visible.sync="attachMode" v-if="attachMode">
                <instant-search :data-url="searchUrl" @select="onItemSelected" :width="widthSelect"></instant-search>

                <div slot="footer" class="dialog-footer">
                    <el-button @click="attachMode = false">Annuler</el-button>
                    <el-button type="primary" @click="attach">Ajouter</el-button>
                </div>
            </el-dialog>

            <el-button size="small" @click="attachMode = true">+ Ajouter un nouveau</el-button>
            &nbsp;

            <el-tag closable

                    v-for="(tag, index) in values"
                    :key="'tag-'+index"
                    @close="remove(tag)"
            >
                <input type="hidden" :name="name+'[]'" :value="tag[keyName]">
                {{ tag[labelName] }}
            </el-tag>
        </section>

        <div v-if="queuedToRemove.length">
            <h5>Trash</h5>
            <el-tag closable
                    type="warning"
                    v-for="(tag, index) in queuedToRemove"
                    :key="'tag-'+index"
                    @close="restore(tag)"
            >
                {{ tag[labelName] }}
            </el-tag>
        </div>
    </div>
</template>

<script>
export default {
    props: {
        items: {
            type: Array,
            default: []
        },
        searchUrl: String,
        name: String,
        keyName: {
            type: String,
            default: 'id'
        },
        labelName: {
            type: String,
            default: 'name'
        },
        widthSelect: {
            type: Number,
            default: 50

        }
    },
    data() {
        return {
            collection: [],
            current: [],
            attachMode: false,
            queuedToRemove: [],
        }
    },
    mounted() {
        this.collection = this.items || [];
    },
    computed: {
        values() {
            return this.collection.filter((item) => -1 === this.queuedToRemove.indexOf(item));
        }
    },
    methods: {
        remove(tag) {
            this.queuedToRemove.push(tag);
        },
        restore(tag) {
            this.queuedToRemove = this.queuedToRemove.filter(item => tag[this.keyName] !== item[this.keyName]);
        },
        onItemSelected($event) {
            let me = this;
            this.current =[];
            $event.forEach(function (ev) {
                me.current.push({
                    [me.keyName]: ev.id,
                    [me.labelName]: ev.name,
                });
            })
        },
        attach() {
            let me = this;
            if (this.current.length) {
                this.current.forEach(function (current) {
                    let exists = me.collection.filter(item => item[me.keyName] === current.id);
                    if (!exists.length) {
                        me.collection.push(current);
                    }
                })


                this.current = [];
            }
            this.attachMode = false;
        }
    }
}
</script>

<style lang="scss">
.el-tag {
    margin-right: 10px;
    margin-bottom: 10px;
}
</style>

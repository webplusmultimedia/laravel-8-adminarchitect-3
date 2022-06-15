<template>
    <div>
        <div :class="(property.type==='checkbox')?'checkbox':'form-group'" v-for="(property,index) in getProperties(customProperties)" :key="index">

            <label :for="property.name">
                <input :type="property.type" v-model="proprietes[property.name]"
                       :placeholder="property.label" :id="property.name" v-if="property.type==='checkbox'"/>
                {{ property.label }}</label>
            <textarea v-model="proprietes[property.name]" rows="6" class="form-control" :id="property.name"
                      v-if="property.type==='textarea' || property.type==='textareatiny'" :data-editor="property.type==='textareatiny'?'tinymcemini':''" ></textarea>
            <input :type="property.type" class="form-control" v-model="proprietes[property.name]"
                   :placeholder="property.label" :id="property.name" v-else-if="property.type!=='checkbox'"/>
        </div>
    </div>
</template>

<script>
import _str from "lodash/string";

export default {
    name: "InputsField",
    props: {
        customProperties: {
            type: Array,
            default() {
                return []
            }
        },
        proprietes: {type: Object, value: {}},
    },
    data() {
        return {
            custom_types: [
                'textarea',
                //'textareatiny',
                'number',
                'url',
                'checkbox'
            ],

            propriete: {
                type: Object,
                default() {
                    return {}
                }
            }

        }
    },
    methods: {
        /**
         *
         * @param {Array} properties
         * @return {Array<?Object>}
         */
        getProperties: function (properties) {
            let custom_properties = [],
                custom_types = this.custom_types
            properties.forEach(function (property) {
                let type = 'text', label = _str.startCase(property), name = property,
                    search = custom_types.find(function (type) {
                        return _str.endsWith(property, '_' + type)
                    })

                if (search) {
                    custom_properties.push({
                        label: _str.startCase(_str.trimEnd(property, search)),
                        name: _str.replace(property, '_'.concat(search),''),
                        type: search
                    })
                } else {
                    custom_properties.push({label, name, type})
                }
            })

            return custom_properties;
        }
    }
}
</script>

<style scoped>
input,textarea{
    font-weight: normal;
}
</style>

<template>
    <div :class="$attrs.class">
        <label v-if="label" class="form-label" :for="id">{{ label }}:</label>
        <div class="form-input">
            <input :id="id" ref="input" v-bind="{ ...$attrs, class: null }" :class="{ error: error }" :type="type" @input="$emit('update:modelValue', $event.target.value)" multiple @change="previewImage" />
            <p>Image Preview:</p>
            <div class="w-full flex flex-wrap">
                <div v-for="(image, key) in imageSrcs" :key="key" class="p-3 self-center">
                    <button type="button" @click="removeImage(key)" class="float-right rounded w-6 bg-red-500">
                        <span class="text-2xl text-white">X</span>
                    </button>
                    <div class="w-52">
                        <img :src="generateUrl(image)" alt="Preview" />
                    </div>
                </div>
            </div>
        </div>
        <div v-if="error" class="form-error">{{ error }}</div>
    </div>
</template>

<script>
import { v4 as uuid } from 'uuid'
import axios from 'axios'
import { useForm } from '@inertiajs/vue3';

export default {
    inheritAttrs: false,
    props: {
        id: {
            type: String,
            default() {
                return `text-input-${uuid()}`
            },
        },
        type: {
            type: String,
            default: 'text',
        },
        error: String,
        label: String,
        editImages: {
            type: [Object, File, String],
            default: '',
        },
        imageDeleteUrl : String,
    },
    data(){
        return {
            images : {},
            imageSrcs : {...this.editImages} || {},
        }
    },
    emits: ['update:modelValue', 'imageupload'],
    methods: {
        focus() {
            this.$refs.input.focus()
        },
        select() {
            this.$refs.input.select()
        },
        setSelectionRange(start, end) {
            this.$refs.input.setSelectionRange(start, end)
        },
        previewImage(event) {
            const files = event.target.files;

            Array.from(files).forEach((file) => {
                if (file && file.type.startsWith("image/")) {
                    let uniqKey = Object.keys(this.imageSrcs).length + 1;
                    this.images[uniqKey] = file;
                    let url = URL.createObjectURL(file);
                    this.imageSrcs[uniqKey] = url;
                }
            });
            this.$emit('imageupload', this.images);
        },
        removeImage(key) {
            let url = this.imageDeleteUrl.replace('imageId', key);
            if(this.imageSrcs[key].startsWith('blob:')){
                delete this.images[key];
                delete this.imageSrcs[key];
            } else {
                if(confirm('Are you sure you want to remove this Image')){
                    let form = useForm({});
                    form.delete(url, {
                        onSuccess : () => {
                            delete this.images[key];
                            delete this.imageSrcs[key];
                        },
                        onError: (error) => {
                            console.error(error);
                        }
                    })
                }
            }
            this.$emit('imageupload', this.images);
        },
        generateUrl(image){
            if(image.startsWith('blob:')) {
                return image;
            } else {
                return `/storage/product/${image}`;
            }
        }
    },
}
</script>
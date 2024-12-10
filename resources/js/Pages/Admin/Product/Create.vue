<template>

  <Head title="Product Create" />
  <h1 class="mb-8 text-3xl font-bold">Create Product</h1>
  <div class="bg-white rounded-md shadow overflow-x-auto">
    <div class="w-full bg-white rounded-md shadow overflow-hidden">
      <form @submit.prevent="submitForm()">
        <div>
          <h1 class="text-lg font-bold pt-2 px-6">Basic Info:-</h1>
        </div>
        <div class="flex flex-wrap p-6">
          <div class="columns-2 w-full">
            <TextInput type="text" v-model="form.name" :error="form.errors.name" class="pb-8 w-full h-28" label="Name" />
            <SelectInput v-model="form.category_id" :error="form.errors.category_id" class="pb-8 w-full h-28" label="Category" >
              <option value="">Select Category</option>
              <option v-for="(category, key) in categories" :value="key">{{  category }}</option> 
            </SelectInput>
          </div>
          <div class="columns-2 w-full">
            <TextInput v-model="form.mrp" :error="form.errors.mrp" class="pb-8 w-full h-28" type="number" min="0" label="MRP" />
            <TextInput v-model="form.rate" :error="form.errors.rate" class="pb-8 w-full h-28" type="number" min="0" label="Rate" />
          </div>
          <TextareaInput v-model="form.description" :error="form.errors.description" class="pb-8 w-full" type="text" label="Description" />
          <MultipaleImage v-model="form.images" :error="form.errors.images" :editImages="product?.images" @imageupload="imageupload" :imageDeleteUrl="imageDeleteUrl" class="pb-8 w-full" type="file" label="Image" />
          <div class="flex items-center">
            <label for="link-checkbox">Is Active?</label>
            <input id="link-checkbox" type="checkbox" :true-value="1" :false-value="0" v-model="form.status" class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 rounded dark:bg-gray-700 dark:border-gray-600 ml-3">
          </div>
        </div>
        <div class="p-6 text-red-500" v-if="Object.keys(form.errors).length > 0">
          {{$page.props.errors[Object.keys($page.props.errors)[0]]}}
        </div>
        <div class="flex items-center px-8 py-4 bg-gray-50 border-t border-gray-100">
          <Link class="text-black bg-gray-200 p-3 rounded" :href="route('admin.products')">Back</Link>
          <LoadingButton :loading="form.processing" class="btn-indigo ml-auto" type="submit">Update
          </LoadingButton>
        </div>
      </form>
    </div>
  </div>
</template>

<script>
import Layout from '@/Shared/Layout.vue'
import { Head, Link, useForm } from '@inertiajs/vue3'
import TextInput from '@/Shared/TextInput.vue'
import TextareaInput from '@/Shared/TextareaInput.vue'
import LoadingButton from '@/Shared/LoadingButton.vue'
import MultipaleImage from '@/Shared/MultipaleImage.vue'
import SelectInput from '@/Shared/SelectInput.vue'

export default {
  props : {
    categories : Object,
    product : Object,
  },
  components: {
    Head,
    Link,
    useForm,
    TextInput,
    TextareaInput,
    LoadingButton,
    MultipaleImage,
    SelectInput,
  },
  layout: Layout,
  data() {
    return {
      form: {
        name: this.product?.name || '',
        category_id: this.product?.category_id || '',
        mrp: this.product?.mrp || '',
        rate: this.product?.rate || '',
        description: this.product?.description || '',
        images : '',
        status: (this.product?.status !== undefined ? this.product?.status : 1) && 1,
        errors: {},
      },
      imageDeleteUrl: (this.product?.id ? route('admin.product.deleteImage', [this.product.id, 'imageId']) : ''),
    }
  },
  methods: {
    imageupload(files) {
      this.form.images = files;
    },
    submitForm(){
      let url = route('admin.product.save');
      if(this.product?.id){
        this.form._method = 'put';
        url = route('admin.product.update', this.product.id);
      }
      this.form = useForm(this.form);
      this.form.post(url, {
        onSuccess : () => {
          this.form.reset();
        },
        onError: (error) => {
          this.form.errors = error;
          console.error(error);
        },
      });
    }
  },
}
</script>

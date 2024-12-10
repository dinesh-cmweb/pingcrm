<template>

  <Head title="Product Create" />
  <h1 class="mb-8 text-3xl font-bold">Create Product</h1>
  <div class="bg-white rounded-md shadow overflow-x-auto">
    <div class="w-full bg-white rounded-md shadow overflow-hidden">
      <form @submit.prevent="submitForm()">
        <div class="flex flex-wrap p-6">
          <div class="columns-2 w-full">
            <TextInput type="text" v-model="form.name" :error="form.errors.name" class="pb-8 w-full h-28"
              label="Name" />
            <SelectInput v-model="form.category_id" :error="form.errors.category_id" class="pb-8 w-full h-28"
              label="Category">
              <option value="">Select Category</option>
              <option v-for="(category, key) in categories" :value="key">{{ category }}</option>
            </SelectInput>
          </div>
          <div class="columns-2 w-full">
            <TextInput v-model="form.mrp" :error="form.errors.mrp" class="pb-8 w-full h-28" type="number" min="0"
              label="MRP" />
            <TextInput v-model="form.rate" :error="form.errors.rate" class="pb-8 w-full h-28" type="number" min="0"
              label="Rate" />
          </div>
          <TextareaInput v-model="form.description" :error="form.errors.description" class="pb-8 w-full" type="text"
            label="Description" />
          <div class="flex items-center">
            <label for="link-checkbox">Is Active?</label>
            <input id="link-checkbox" type="checkbox" :true-value="1" :false-value="0" v-model="form.status"
              class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 rounded dark:bg-gray-700 dark:border-gray-600 ml-3">
          </div>
        </div>
        <div class="p-6 text-red-500" v-if="Object.keys(form.errors).length > 0">
          {{ $page.props.errors[Object.keys($page.props.errors)[0]] }}
        </div>
        <div class="flex items-center px-8 py-4 bg-gray-50 border-t border-gray-100">
          <Link class="text-black bg-gray-200 p-3 rounded" :href="route('admin.products')">Back</Link>
          <LoadingButton :loading="form.processing" class="btn-indigo ml-auto" type="submit">Update
          </LoadingButton>
        </div>
      </form>
    </div>
  </div>
  <div v-if="product?.id" class="bg-white rounded-md shadow my-6">
    <h1 class="text-2xl ml-10">Create Item:</h1>
    <div class="flex items-center ml-10 mt-6">
      <button class="btn-indigo" @click="open = true">
        <span>Create</span>
        <span class="hidden md:inline">&nbsp;Item</span>
      </button>
    </div>
    <div class="w-full flex flex-wrap p-10">
      <div v-for="(media, key) in product?.medias" :key="key" class="p-3 self-center border">
        <button type="button" @click="removeItem(route('admin.products2.item.delete', [product.id, media.id]))"
          class="float-right rounded w-8">
          <icon name="trash" class="mr-2 w-7 h-7 fill-red-500" />
        </button>
        <button type="button" @click="editItem(route('admin.products2.item.edit', [product.id, media.id]))"
          class="float-right rounded w-10">
          <icon name="edit" class="mr-2 w-7 h-7" />
        </button>
        <div class="w-52 mt-10">
          <img :src="`/storage/product/${media.url}`" :alt="media.id">
        </div>
      </div>
    </div>
    <ItemCreate v-if="open" @close="() => { open = false, item = {} }" :item="item" :productId="product?.id" />
  </div>
</template>

<script>
import Layout from '@/Shared/Layout.vue'
import { Head, Link, useForm } from '@inertiajs/vue3'
import TextInput from '@/Shared/TextInput.vue'
import TextareaInput from '@/Shared/TextareaInput.vue'
import LoadingButton from '@/Shared/LoadingButton.vue'
import SelectInput from '@/Shared/SelectInput.vue'
import ItemCreate from './ItemCreate.vue'
import Icon from '@/Shared/Icon.vue'
import axios from 'axios'

export default {
  props: {
    categories: Object,
    product: Object,
  },
  components: {
    Head,
    Link,
    useForm,
    TextInput,
    TextareaInput,
    LoadingButton,
    SelectInput,
    ItemCreate,
    Icon,
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
        images: '',
        status: (this.product?.status !== undefined ? this.product?.status : 1) && 1,
        errors: {},
      },
      open: false,
      item: {},
    }
  },
  methods: {
    submitForm() {
      let url = route('admin.product2.save');
      if (this.product?.id) {
        this.form._method = 'put';
        url = route('admin.product2.update', this.product.id);
      }
      this.form = useForm(this.form);
      this.form.post(url, {
        onSuccess: () => {
          this.form.reset();
        },
        onError: (error) => {
          this.form.errors = error;
          console.error(error);
        },
      });
    },
    async editItem(url) {
      const res = await axios.get(url);
      this.item = res.data;
      this.open = true;
    },
    removeItem(url) {
      let form = useForm({});
      if(confirm('Are you sure you want to delete this item?')){
        form.delete(url, {
          onError: (error) => {
            console.error(error);
          }
        });
      }
    },
  },
}
</script>

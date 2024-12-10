<template>
  <div>

    <Head title="Category" />
    <h1 class="mb-8 text-3xl font-bold">Categories</h1>
    <div class="flex items-center justify-end mb-6">
      <button class="btn-indigo" @click="open = true">
      <span>Create</span>
      <span class="hidden md:inline">&nbsp;Category</span>
      </button>
    </div>
    <div class="bg-white rounded-md shadow overflow-x-auto">
      <table class="w-full whitespace-nowrap">
        <thead>
          <tr class="text-left font-bold">
            <th class="pb-4 border px-2.5 py-2">Image</th>
            <th class="pb-4 border px-2.5 py-2">Name</th>
            <th class="pb-4 border px-2.5 py-2">Slug</th>
            <th class="pb-4 border px-2.5 py-2">Status</th>
            <th class="pb-4 border px-2.5 py-2">Action</th>
          </tr>
        </thead>
        <tbody>
          <tr v-for="category in categories.data" :key="category.id" class="hover:bg-gray-100 focus-within:bg-gray-100">
            <td class="border px-2.5 py-2 w-36">
              <img v-if="category.image" :src="`/storage/category/${category.image}`" :alt="category.name">
            </td>
            <td class="border px-2.5 py-2">
              {{ category.name }}
            </td>
            <td class="border px-2.5 py-2">
              {{ category.slug }}
            </td>
            <td class="border px-2.5 py-2">
              <span class="rounded p-1 font-bold text-xs" :class="category.status ? 'bg-green-600 text-white': 'bg-yellow-500 text-black'">
                {{ category.status ? 'Active' : 'Inactive' }}
              </span>
            </td>
            <td class="border px-2.5 py-2">
              <button type="button" class="inline-block" @click="editFrom(route('admin.category.edit', category.id))">
                <icon name="edit" class="mr-2 w-7 h-7" />
              </button>
              <button type="button" @click="deleteData(route('admin.category.delete', category.id))" class="w-fit">
                <icon name="trash" class="mr-2 w-7 h-7 fill-red-500" />
              </button>
            </td>
          </tr>
          <tr v-if="categories.data.length === 0">
            <td class="px-2.5 py-2 border-t" colspan="5">No Categories found.</td>
          </tr>
        </tbody>
      </table>
    </div>
    <pagination class="mt-6" :links="categories.links" />
    <Create v-if="open"  @close="() => { open = false; category = {};}" :category="category"/>
  </div>
</template>

<script>
import { Head, Link, useForm } from '@inertiajs/vue3'
import Icon from '@/Shared/Icon.vue'
import Layout from '@/Shared/Layout.vue'
import Pagination from '@/Shared/Pagination.vue'
import Create from './Create.vue'
import axios from 'axios'

export default {
  components: {
    Head,
    Icon,
    Link,
    Pagination,
    Create,
  },
  layout: Layout,
  props: {
    categories: Object,
  },
  data() {
    return {
      open: false,
      category: {},
    }
  },
  methods : {
    async editFrom(url) {
      const res = await axios.get(url);
      this.category = res.data;
      this.open = true;
    },
    deleteData(url){
      if(confirm("Are you sure you want to delete?")){
        let form = useForm({});
        form.delete(url, {
          onError: (error) => {
            console.error(error);
          }
        })
      }
    }
  },
}
</script>

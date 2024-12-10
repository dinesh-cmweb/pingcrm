<template>
  <div>

    <Head title="Products" />
    <h1 class="mb-8 text-3xl font-bold">Products</h1>
    <div class="flex items-center justify-end mb-6">
      <Link class="btn-indigo float-right" :href="route('admin.product.create')">
      <span>Create</span>
      <span class="hidden md:inline">&nbsp;Product</span>
      </Link>
    </div>
    <div class="bg-white rounded-md shadow overflow-x-auto">
      <table class="w-full whitespace-nowrap">
        <thead>
          <tr class="text-left font-bold">
            <th class="pb-4 border px-2.5 py-2">Name</th>
            <th class="pb-4 border px-2.5 py-2">Slug</th>
            <th class="pb-4 border px-2.5 py-2">MRP.</th>
            <th class="pb-4 border px-2.5 py-2">Rate</th>
            <th class="pb-4 border px-2.5 py-2">Status</th>
            <th class="pb-4 border px-2.5 py-2">Action</th>
          </tr>
        </thead>
        <tbody>
          <tr v-for="product in products.data" :key="product.id" class="hover:bg-gray-100 focus-within:bg-gray-100">
            <td class="border px-2.5 py-2">
              {{ product.name }}
            </td>
            <td class="border px-2.5 py-2">
              {{ product.slug }}
            </td>
            <td class="border px-2.5 py-2">
              {{ product.mrp }}
            </td>
            <td class="border px-2.5 py-2">
              {{ product.rate }}
            </td>
            <td class="border px-2.5 py-2">
              <span class="rounded p-1 font-bold text-xs" :class="product.status ? 'bg-green-600 text-white': 'bg-yellow-500 text-black'">
                {{ product.status ? 'Active' : 'Inactive' }}
              </span>
            </td>
            <td class="border px-2.5 py-2">
              <Link class="inline-block" :href="route('admin.product.edit', product.id)">
                <icon name="edit" class="mr-2 w-7 h-7" />
              </Link>
              <button type="button" @click="deleteData(route('admin.product.delete', product.id))" class="w-fit">
                <icon name="trash" class="mr-2 w-7 h-7 fill-red-500" />
              </button>
            </td>
          </tr>
          <tr v-if="products.data.length === 0">
            <td class="px-2.5 py-2 border-t" colspan="5">No Products found.</td>
          </tr>
        </tbody>
      </table>
    </div>
    <pagination class="mt-6" :links="products.links" />
  </div>
</template>

<script>
import { Head, Link, useForm } from '@inertiajs/vue3'
import Icon from '@/Shared/Icon.vue'
import Layout from '@/Shared/Layout.vue'
import Pagination from '@/Shared/Pagination.vue'

export default {
  components: {
    Head,
    Icon,
    Link,
    Pagination,
  },
  layout: Layout,
  props: {
    products: Object,
  },
  data() {
    return {
    }
  },
  methods : {
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

<script setup lang="ts">
import { computed, ref } from 'vue'
import { Head, router } from '@inertiajs/vue3'
import AppLayout from '@/layouts/AppLayout.vue'
import { Card, CardContent, CardDescription, CardFooter, CardHeader, CardTitle } from '@/components/ui/card'
import { Button } from '@/components/ui/button'

interface Course {
  id: number
  title: string
  description: string
  instructor: string
  price: number
  duration_hours: number
  level: string
  created_at: string
  updated_at: string
}

interface CursorPaginatedCourses {
  data: Course[]
  next_cursor: string | null
  prev_cursor: string | null
  per_page: number
  next_page_url: string | null
  prev_page_url: string | null
}

const props = defineProps<{
  courses: CursorPaginatedCourses
}>()

const loading = ref(false)

const hasMore = computed(() => {
  return props.courses.next_page_url !== null
})

const loadMore = () => {
  if (loading.value || !hasMore.value) return
  
  loading.value = true
  
  router.visit(props.courses.next_page_url!, {
    only: ['courses'],
    preserveState: true,
    preserveUrl: true,
    onFinish: () => {
      loading.value = false
    }
  })
}
</script>

<template>
  <Head title="Infinite Scroll Sandbox" />

  <AppLayout>
    <div class="container mx-auto py-8">
      <div class="mb-8">
        <h1 class="text-3xl font-bold">Infinite Scroll Sandbox</h1>
        <p class="text-muted-foreground mt-2">
          Exploring infinite scrolling with cursor pagination
        </p>
      </div>

      <div class="grid gap-6 md:grid-cols-2 lg:grid-cols-3">
        <Card v-for="course in courses.data" :key="course.id" class="h-fit">
          <CardHeader>
            <div class="flex items-start justify-between">
              <div class="flex-1">
                <CardTitle class="text-lg">{{ course.title }}</CardTitle>
                <CardDescription class="mt-1">by {{ course.instructor }}</CardDescription>
              </div>
              <span 
                :class="[
                  'px-2 py-1 text-xs font-medium rounded-full',
                  course.level === 'beginner' ? 'bg-green-100 text-green-800' : 
                  course.level === 'intermediate' ? 'bg-yellow-100 text-yellow-800' : 
                  'bg-red-100 text-red-800'
                ]"
              >
                {{ course.level }}
              </span>
            </div>
          </CardHeader>
          <CardContent>
            <p class="text-sm text-muted-foreground line-clamp-3">
              {{ course.description }}
            </p>
          </CardContent>
          <CardFooter class="flex justify-between items-center">
            <div class="text-sm text-muted-foreground">
              {{ course.duration_hours }} hours
            </div>
            <div class="font-semibold text-lg">
              ${{ course.price.toFixed(2) }}
            </div>
          </CardFooter>
        </Card>
      </div>

      <!-- Load More Button -->
      <div class="mt-8 text-center">
        <p class="text-sm text-muted-foreground mb-4">
          Showing {{ courses.data.length }} courses
        </p>
        <Button 
          v-if="hasMore"
          @click="loadMore"
          :disabled="loading"
          class="px-8 py-2"
        >
          {{ loading ? 'Loading...' : 'Load More' }}
        </Button>
        <p v-else class="text-sm text-muted-foreground">
          No more courses to load
        </p>
      </div>
    </div>
  </AppLayout>
</template>
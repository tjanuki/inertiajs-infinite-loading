<script setup lang="ts">
import { Head } from '@inertiajs/vue3'
import AppLayout from '@/layouts/AppLayout.vue'
import { Card, CardContent, CardDescription, CardFooter, CardHeader, CardTitle } from '@/components/ui/card'

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

interface PaginatedCourses {
  data: Course[]
  current_page: number
  last_page: number
  per_page: number
  total: number
  next_page_url: string | null
  prev_page_url: string | null
}

defineProps<{
  courses: PaginatedCourses
}>()
</script>

<template>
  <Head title="Infinite Scroll Sandbox" />

  <AppLayout>
    <div class="container mx-auto py-8">
      <div class="mb-8">
        <h1 class="text-3xl font-bold">Infinite Scroll Sandbox</h1>
        <p class="text-muted-foreground mt-2">
          Exploring infinite scrolling with {{ courses.total }} courses
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

      <!-- Pagination info -->
      <div class="mt-8 text-center text-sm text-muted-foreground">
        Showing {{ courses.data.length }} of {{ courses.total }} courses
        (Page {{ courses.current_page }} of {{ courses.last_page }})
      </div>
    </div>
  </AppLayout>
</template>
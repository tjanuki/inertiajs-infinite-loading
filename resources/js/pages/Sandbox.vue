<script setup lang="ts">
import { computed, ref, nextTick, onMounted } from 'vue'
import { Head, router } from '@inertiajs/vue3'
import { useIntersectionObserver } from '@vueuse/core'
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
const scrollContainer = ref<HTMLElement>()
const previousScrollHeight = ref(0)
const loadMoreTrigger = ref<HTMLElement>()

const reversedCourses = computed(() => {
  return [...props.courses.data].reverse()
})

const hasMore = computed(() => {
  return props.courses.next_page_url !== null
})

const reachedEnd = computed(() => {
  return !hasMore.value
})

const loadMore = () => {
  if (loading.value || !hasMore.value) return

  loading.value = true

  // Save current scroll position
  if (scrollContainer.value) {
    previousScrollHeight.value = scrollContainer.value.scrollHeight
  }

  router.visit(props.courses.next_page_url!, {
    only: ['courses'],
    preserveState: true,
    preserveUrl: true,
    onFinish: () => {
      loading.value = false
      // Adjust scroll position after new content loads
      nextTick(() => {
        if (scrollContainer.value) {
          const newScrollHeight = scrollContainer.value.scrollHeight
          const heightDifference = newScrollHeight - previousScrollHeight.value
          scrollContainer.value.scrollTop += heightDifference
        }
      })
    }
  })
}

// Set up intersection observer for automatic loading
const { stop } = useIntersectionObserver(
  loadMoreTrigger,
  ([{ isIntersecting }]) => {
    if (isIntersecting && hasMore.value && !loading.value) {
      loadMore()
    }
  },
  {
    threshold: 0.1,
    rootMargin: '100px'
  }
)

onMounted(() => {
  nextTick(() => {
    if (scrollContainer.value) {
      scrollContainer.value.scrollTop = scrollContainer.value.scrollHeight
    }
  })
})
</script>

<template>
  <Head title="Infinite Scroll Sandbox" />

  <AppLayout>
    <div class="flex flex-col h-screen px-2">
      <div class="container mx-auto py-8 flex-shrink-0">
        <div class="mb-8">
          <h1 class="text-3xl font-bold">Infinite Scroll Sandbox</h1>
          <p class="text-muted-foreground mt-2">
            Exploring infinite scrolling with cursor pagination
          </p>
        </div>
      </div>

      <div ref="scrollContainer" class="flex-1 overflow-y-auto container mx-auto px-8">
        <!-- Auto Load More with Intersection Observer -->
        <div class="mb-8 text-center">
          <p class="text-sm text-muted-foreground mb-4">
            Showing {{ courses.data.length }} courses
          </p>
          <div v-if="hasMore" ref="loadMoreTrigger" class="py-4">
            <Button
              @click="loadMore"
              :disabled="loading"
              class="px-8 py-2"
            >
              {{ loading ? 'Loading...' : 'Load More' }}
            </Button>
          </div>
          <p v-else class="text-sm text-muted-foreground">
            No more courses to load
          </p>
        </div>

        <div class="grid gap-6 md:grid-cols-2 lg:grid-cols-3">
        <Card v-for="course in reversedCourses" :key="course.id" class="h-fit">
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
      </div>
    </div>
  </AppLayout>
</template>

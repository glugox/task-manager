<script setup lang="ts">
import {useForm, router, usePage} from '@inertiajs/vue3'
import Sortable from 'sortablejs'
import {onMounted, ref, watch} from 'vue'
import vSelect from 'vue-select'
import 'vue-select/dist/vue-select.css'

import { Task, Project } from "../../tepes";

const { project_id, tasks, projects } = defineProps({
    project_id: [Number, String],
    tasks: {
        type: Array as () => Task[],
        required: true,
    },
    projects: {
        type: Array as () => Project[],
        required: true,
    },
})

const successMessage = ref('');
const showNewProjectInput = ref(false)
const form = useForm({
    name: '',
    project_id: null,             // used when selecting existing project
    new_project_name: '',    // used when typing a new one
    errors: {}
})

const page = usePage();
// Find the project object matching the project_id passed from backend
const selectedProject = ref(
    projects.find((p) => p.id === Number(page.props.project_id)) || null
);

watch(selectedProject, (newProject) => {
    const params: Record<string, any> = {};
    if (selectedProject.value) {
        params.project_id = selectedProject.value.id;
    }
    router.get('/', params, {
        preserveScroll: true,
        preserveState: true,
    });
});

function submitForm() {
    successMessage.value = '';
    form.post('/tasks', {
        onSuccess: () => {
            successMessage.value = 'Task added successfully!';
            form.reset('name', 'project_id', 'new_project_name');
        },
        onError: () => {
            successMessage.value = '';
        },
        onFinish: () => {
            // after 2 seconds, reset the success message and errors
            setTimeout(() => {
                successMessage.value = '';
                form.errors = {};
            }, 2000);
        },
    });
}

/* On component mount, initialize Sortable on the task list */
onMounted(() => {
    const el = document.getElementById('task-list') as HTMLElement | null

    if (el) {
        new Sortable(el, {
            animation: 150,
            onEnd: reorderTasks,
        })
    } else {
        console.warn('Element #task-list not found')
    }
})

const toggleNewProject = () => {
    showNewProjectInput.value = !showNewProjectInput.value
    form.project_id = null
    form.new_project_name = ''
}

const reorderTasks = () => {
    const ids = [...document.querySelectorAll('#task-list li')].map(el => (el as HTMLElement).dataset.id);
    router.post('/tasks/reorder', { order: ids });
}

const deleteTask = (id: number | string) => router.delete(`/tasks/${id}`)

</script>

<style scoped>
.task-manager ::placeholder {
    color: #999999;
}
</style>

<template>
    <div  class="task-manager max-w-xl mx-auto p-4">
        <!-- Add new task label and form -->
        <p class="text-lg font-bold mb-2">Add New Task</p>
        <form id="new_task_form" @submit.prevent="submitForm" class="mb-10 space-y-2">

            <!-- Success message -->
            <p v-if="successMessage" class="text-green-600 mt-2">{{ successMessage }}</p>

            <!-- Task name -->
            <div>
                <input
                    v-model="form.name"
                    class="border border-gray-300 rounded py-1 px-2 w-full"
                    placeholder="New Task"
                />
                <div v-if="form.errors.name" class="text-red-500 text-sm">{{ form.errors.name }}</div>
            </div>
            <!-- Select existing project -->
            <div v-if="!showNewProjectInput">
                <v-select
                    v-model="form.project_id"
                    :options="projects"
                    label="name"
                    :reduce="(project: Project) => project.id"
                    placeholder="Select a project"
                />
                <div v-if="form.errors.project_id" class="text-red-500 text-sm">{{ form.errors.project_id }}</div>
                <!-- Toggle input -->
                <button type="button" class="text-blue-600 text-sm mt-1" @click="toggleNewProject">
                    + Add new project
                </button>
            </div>
            <!-- Input for new project -->
            <div v-else>
                <input
                    v-model="form.new_project_name"
                    type="text"
                    class="border border-gray-300 rounded py-1 px-2 w-full "
                    placeholder="New project name"
                />
                <div v-if="form.errors.new_project_name" class="text-red-500 text-sm">{{ form.errors.new_project_name }}</div>
                <button type="button" class="text-gray-500 text-sm mt-1" @click="toggleNewProject">
                    ← Cancel
                </button>
            </div>
            <!-- Submit button -->
            <button
                class="mt-2 bg-blue-500 text-white px-3 py-1 rounded"
                :disabled="form.processing"
            >
                <span v-if="form.processing">Processing...</span>
                <span v-else>Add Task</span>
            </button>
        </form>
        <!-- Tasks title and project filter -->
        <div class="mb-2 flex items-center justify-between">
            <h2 class="text-xl font-bold">Tasks</h2>
            <div class="justify-end">
                <v-select
                    :options="projects"
                    v-model="selectedProject"
                    placeholder="Filter by project"
                    label="name"
                    class="w-64"
                />

            </div>
        </div>
        <!-- Task list with Sortable -->
        <div v-if="tasks.length === 0" class="text-gray-500 italic mt-4">
            No tasks yet. Add one above!
        </div>
        <ul v-else id="task-list" class="space-y-2">
            <li v-for="task in tasks" :key="task.id" :data-id="task.id" class="p-2 bg-gray-100 cursor-move flex justify-between">
                <span>{{ task.name }}</span>
                <button @click="task.id && deleteTask(task.id)" class="text-red-500">Delete</button>
            </li>
        </ul>
    </div>
</template>

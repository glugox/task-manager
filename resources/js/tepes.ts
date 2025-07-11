export interface Project {
    id?: number
    name: string
}

export interface Task {
    id?: number
    project_id: number
    name: string
    project: Project
}

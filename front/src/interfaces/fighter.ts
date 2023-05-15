export interface IFighter {
    id: string
    firstname: string
    lastname: string
    height: number
    weight: number
    nationality: string
    gender: string
    birthdate: string
    imageFile: File | null
    imageName: string
    imageSize: string
}

export interface CreateFighter {
    firstname: string
    lastname: string
    height: number
    weight: number
    nationality: string
    gender: string
    birthdate: string
    imageFile: Blob | null
    imageName: string
    imageSize: string
}
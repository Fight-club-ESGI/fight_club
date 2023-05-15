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
    imageFile: Blob | string
    imageName: string
    imageSize: string
}

export interface UpdateFighter {
    firstname: string
    lastname: string
    height: number
    weight: number
    nationality: string
    gender: string
    birthdate: string
    imageFile: Blob | string
    imageName: string
    imageSize: string
}
export function formatNumber(price: number = 0): string {
    return price.toFixed(2).replace('.', ',');
}

export function constructFullName(firstname: string = '', lastname: string = ''): string {
    return firstname + ' ' + lastname;
}

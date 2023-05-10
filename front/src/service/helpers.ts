export function formatNumber(price: number | string): string {
    if (typeof price === 'string') {
        return parseInt(price).toFixed(2).replace('.', ',');
    }
    return price.toFixed(2).replace('.', ',');
}

export function constructFullName(firstname: string = '', lastname: string = ''): string {
    return firstname + ' ' + lastname;
}

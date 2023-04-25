export function formatMoney(price: number): string {
    return price.toFixed(2).replace('.', ',');
}

export function constructFullName(firstname: string = '', lastname: string = ''): string {
    return firstname + ' ' + lastname;
}

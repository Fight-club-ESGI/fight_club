export function formatMoney(price:number): string {
    return price.toFixed(2).replace('.', ',');
}
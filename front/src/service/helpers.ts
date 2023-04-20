export function formatMoneyOrRating(price:number): string {
    return price.toFixed(2).replace('.', ',');
}
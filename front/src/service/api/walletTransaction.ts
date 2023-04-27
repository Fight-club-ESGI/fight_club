import { client } from "..";
const namespace = "wallet_transactions"

class Wallet {

    async _walletHistory() {
        try {
            const uri = `${namespace}`;
            const res = await client.get(uri);
            return res.data;
        } catch (error) {
            throw error;
        }
    }

    async _confirmation(transaction_id: string) {
        try {
            const uri = `/${namespace}/${transaction_id}/confirmation`
            const res = await client.get(uri);

            return res.data;
        } catch (error) {
            throw error;
        }
    }
}

const walletTransactionService = new Wallet();

export default walletTransactionService;
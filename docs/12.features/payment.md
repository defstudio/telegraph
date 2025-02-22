---
title: 'Payments'
---

## How it works

After creating a Bot you need to have a payment provider token, see https://core.telegram.org/bots/payments for more information about it.

Invoices are sent via the [`Invoice`](#invoice) method.  The bot forms an invoice message with a description of the goods or service, 
amount to be paid, and requested shipping info.

Once the payment is completed  the Bot API sends an Update with the field [`preCheckoutQuery`](#precheckoutquery) to the bot that
contains all the available information about the order. Your bot must reply using answerPrecheckoutQuery through the Webhook Handler  within 
10 seconds after receiving this update or the transaction is canceled. Webhook Handler is already set to send it through the ```handlePreCheckoutQuery()``` method.

The bot may return an error if it can't process the order for any reason. We highly recommend specifying a reason for
failure to complete the order in human readable form (e.g. "Sorry, we're all out of rubber ducks! Would you be interested 
in a cast iron bear instead?"). Telegram will display this reason to the user.

In case the bot confirms the order, Telegram requests the payment provider to complete the transaction. 
If the payment information was entered correctly and the payment goes through, the API will send a receipt
message of the type ```successful_payment``` through the Webhook Handler method  ```handleSuccessfulPayment()```.

See the [`example`](#example) below for further clarification


## Example

Example : System for creating payments and saving invoices  

Let's create a new class that extends the ```WebhookHandler``` class

```php
class TestInvoicingHandler extends WebhookHandler
{

}
```

Let's create a button for the item for sale

```php
public function exampleButton(): void
{
    $this->chat->message('Table')
       ->keyboard(fn(Keyboard $keyboard) => $keyboard->button('Buy')->action('buy')->param('item_id', 42));
}
```

We need to create a function that sends the invoice and , if necessary, stores it

```php
public function buy(int $item_id): void
{
   $invoice = InvoiceModel::create([...]);

   $this->chat->invoice('Attached is the invoice for your order')
        ->currency('EUR')
        ->addItem('Table', 100)
        ->payload($invoice->id)
        ->invoice();
}
```

Once the payment is completed, we can use the Webhook Handler for further operations

```php
    protected function handleSuccessfulPayment(SuccessfulPayment $successfulPayment): void
    {
        //Example : check the invoice data
        $invoice = Invoice::findOrFail($successfulPayment->invoicePayload());

        if ($invoice->total() !== $successfulPayment->totalAmount()) {
           //...errors
        }
    }
```


### Attachments

Invoices can be sent through Telegraph `->invoice()` method:

```php
Telegraph::invoice('Invoice title')
        ->description('Invoice Description')
        ->currency('EUR') //Pass “XTR” for payments in Telegram Stars
        ->addItem('First Item Label', 10)  //Must contain exactly one item for payments in Telegram Stars
        ->addItem('Second Item Label', 10) 
        ->maxTip(70) //Not supported for payments in Telegram Stars
        ->suggestedTips([30,20])
        ->startParameter(10)
        ->image('Invoice Image Link', 20 , 20)
        ->needName() //Ignored for payments in Telegram Stars
        ->needPhoneNumber() //Ignored for payments in Telegram Stars
        ->needEmail() //Ignored for payments in Telegram Stars
        ->needShippingAddress() //Ignored for payments in Telegram Stars
        ->flexible() //Ignored for payments in Telegram Stars
        ->send();
```

### Incoming Data

## `Invoice`

- `->title()` invoice title
- `->description()` invoice description
- `->startParameter()` unique bot deep-linking parameter that can be used to generate this invoice
- `->currency()` invoice currency
- `->totalAmount()` invoice total amount (integer, not float/double)


## `PreCheckoutQuery`

- `->id()` unique query identifier
- `->from()` an instance of the [dto](9.dto.md#user) that triggered the query
- `->currency()` three-letter ISO 4217 currency code, or “XTR” for payments in Telegram Stars
- `->totalAmount()` total price in the smallest units of the currency
- `->invoicePayload()` bot-specified invoice payload
- `->ShippingOptionId()` (optional) identifier of the shipping option chosen by the user
- `->orderInfo()` (optional) an instance of the [`OrderInfo`](#orderinfo) order information provided by the user

## `OrderInfo`

represents information about an order.

- `->name()` (optional) user name
- `->phoneNumber()` (optional) user's phone number
- `->email()` (optional) user email
- `->shippingAddress()` (optional) an instance of [`ShippingAddress`](#shippingAddress) user shipping address


## `ShippingAddress`

represents a shipping address.

- `->countryCode()` two-letter ISO 3166-1 alpha-2 country code
- `->state()` state, if applicable
- `->city()` city
- `->streetLine1()` first line for the address
- `->streetLine2()` second line for the address
- `->postCode()` address post code

## `PreCheckoutQuery`

- `->id()` unique query identifier
- `->from()` an instance of the [dto](9.dto.md#user) that triggered the query
- `->currency()` three-letter ISO 4217 currency code, or “XTR” for payments in Telegram Stars
- `->totalAmount()` total price in the smallest units of the currency
- `->invoicePayload()` bot-specified invoice payload
- `->ShippingOptionId()` (optional) identifier of the shipping option chosen by the user
- `->orderInfo()` (optional) an instance of the [`OrderInfo`](#orderinfo) order information provided by the user


## `SuccessfulPayment`

represents a successful payment.

- `->currency()` three-letter ISO 4217 currency code, or “XTR” for payments in Telegram Stars
- `->totalAmount()` total price in the smallest units of the currency
- `->invoicePayload()` bot-specified invoice payload
- `->subscriptionExpirationDate()` (optional) expiration date of the subscription, in Unix time; for recurring payments only
- `->isRecurring()` (optional) true, if the payment is a recurring payment for a subscription
- `->isFirstRecurring()` (optional) true, if the payment is the first payment for a subscription
- `->shippingOptionId()` (optional) identifier of the shipping option chosen by the user
- `->orderInfo()` (optional) order information provided by the user
- `->telegramPaymentChargeId()` telegram payment identifier
- `->providerPaymentChargeId()` provider payment identifier

> [!NOTE]
> If the buyer initiates a chargeback with the relevant payment provider following this transaction, the funds may be debited from your balance. This is outside of Telegram's control.

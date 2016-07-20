<?php

namespace App\Http\Controllers\invoice;

use Illuminate\Http\Request;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Http\Controllers\UtilityHelper;
use App\AccountGroupModel;

class InvoiceController extends Controller
{
    use UtilityHelper;
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
        $tinvoiceModelList = $this->getHomeOwnerInvoice(null);
        $eInvoiceModelList = array();
        foreach ($tinvoiceModelList as $tinvoiceModel) {
            $eInvoiceModelList[$this->formatString($tinvoiceModel->id)] = $tinvoiceModel;
        }

        return view('invoices.invoices_list',
                        compact('eInvoiceModelList'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $homeOwnerMembersList = $this->getHomeOwnerInformation(null);
        $incomeAccount = $this->getAccountGroups('5'); //get income account titles
        $invoiceModelList = $this->getObjectLastRecord('home_owner_invoice','');
        $invoiceNumber = 1;
        if(count($invoiceModelList)>0){
            $invoiceNumber =  ($invoiceModelList->id + 1);
        }
        $invoiceNumber = $this->formatString($invoiceNumber);
        return view('invoices.create_invoices',
                        compact('homeOwnerMembersList',
                                'invoiceNumber',
                                'incomeAccount'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //Start of getting data from ajax request
        $data = $request->input('data');
        $totalAmount = $request->input('totalAmount');
        $paymentDueDate = $request->input('paymentDueDate');
        $homeownerid = $request->input('homeownerid');
        $homeowner = $this->getObjectFirstRecord('home_owner_information',array('id'=>1));
        //$accountDetailId = $request->input('accountDetailId');
        //End of getting data from ajax request

        //Insert Invoice in Database
        $nInvoiceId = $this->insertRecord('home_owner_invoice',array('home_owner_id' => $homeownerid,
                                                                        'total_amount' => $totalAmount,
                                                                        'payment_due_date' => date('Y-m-d',strtotime($paymentDueDate))));

        $dataToInsert = $this->populateListOfToInsertItems($data,'Revenues','invoice_id',$nInvoiceId,'home_owner_invoice');
        //Insert items in the table
        $this->insertBulkRecord('home_owner_invoice_items',$dataToInsert);
        //Create journal entry
        $this->insertBulkRecord('journal_entry',$this->createJournalEntry($dataToInsert,
                                                                            'Invoice',
                                                                            'invoice_id',
                                                                            $nInvoiceId,
                                                                            'Created invoice for homeowner ' .
                                                                            $homeowner->first_name . ' ' . $homeowner->middle_name . ' ' . $homeowner->last_name,
                                                                            $totalAmount));
        flash()->success('Record successfully created');

        return $nInvoiceId;
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    { 
        $invoice = $this->getHomeOwnerInvoice($id);
        $invoiceNumber = $this->formatString($id);
        return view('invoices.show_invoice',
                        compact('invoice',
                                'invoiceNumber'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $eInvoice = $this->getHomeOwnerInvoice($id);
        $invoiceNumber = $this->formatString($id);
        $incomeAccount = $this->getAccountGroups('5'); //get income account titles
        return view('invoices.edit_invoice',
                        compact('eInvoice',
                                'invoiceNumber',
                                'incomeAccount'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //Start of getting data from ajax request
        $data = $request->input('data');
        $totalAmount = $request->input('totalAmount');
        $paymentDueDate = $request->input('paymentDueDate');
        //End of getting data from ajax request

        //Replace all items in the database
        $toDeleteInvItems = array();
        $toDeleteJournalEntry = array();
        $eInvoice = $this->getHomeOwnerInvoice($id);
        $homeowner = $this->getObjectFirstRecord('home_owner_information',array('id'=>$eInvoice->home_owner_id));
        $this->updateRecord('home_owner_invoice',$id,array('total_amount' => $totalAmount,
                                                            'payment_due_date' => date('Y-m-d',strtotime($paymentDueDate))));
        $eInvoiceItemsList = $this->getObjectRecords('home_owner_invoice_items',array('invoice_id'=>$id));
        $eJournalEntries = $this->getObjectRecords('journal_entry',array('invoice_id'=>$id));
        foreach ($eInvoiceItemsList as $eInvoiceItem) {
            $toDeleteInvItems[] = $eInvoiceItem->id;
        }

        foreach ($eJournalEntries as $eJournalEntry) {
            $toDeleteJournalEntry[] = $eJournalEntry->id;
        }

        $this->deleteRecord('home_owner_invoice_items',$toDeleteInvItems);
        $this->deleteRecord('journal_entry',$toDeleteJournalEntry);


        $dataToInsert = $this->populateListOfToInsertItems($data,'Revenues','invoice_id',$id,'home_owner_invoice');
        $this->insertBulkRecord('home_owner_invoice_items',$dataToInsert);
        //Create journal entry
        $this->insertBulkRecord('journal_entry',$this->createJournalEntry($dataToInsert,
                                                                            'Invoice',
                                                                            'invoice_id',
                                                                            $id,
                                                                            'Created invoice for homeowner ' .
                                                                            $homeowner->first_name . ' ' . $homeowner->middle_name . ' ' . $homeowner->last_name,
                                                                            $totalAmount));
        flash()->success('Record successfully updated');
        return $id;
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $toDeleteInvItems = array();
        $toDeleteJournalEntry = array();
        $eInvoice = $this->getHomeOwnerInvoice($id);
        $eInvoiceItemsList = $this->getObjectRecords('home_owner_invoice_items',array('invoice_id'=>$id));
        $eInvoiceJournalEntries = $this->getObjectRecords('journal_entry',array('invoice_id'=>$id));
        foreach ($eInvoiceItemsList as $eInvoiceItem) {
            $toDeleteInvItems[] = $eInvoiceItem->id;
        }

        foreach ($eInvoiceJournalEntries as $eInvoiceJournalEntry) {
            $toDeleteJournalEntry[] = $eInvoiceJournalEntry->id;
        }
        
        $this->deleteRecord('home_owner_invoice_items',$toDeleteInvItems);
        $this->deleteRecord('journal_entry',$toDeleteJournalEntry);
        $this->deleteRecord('home_owner_invoice',array($id));
        flash()->success('Record successfully deleted')->important();
        return redirect('invoice');
    }
}

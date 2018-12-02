<?php
class Mod_clients_account extends CI_Model{
    public $ID;
    public $ClientName;
    public $BankName;
    public $Reference;
    public $Amount;
    public $Status;
    public $Date;
    public $TransactionCharge;
    public $ConversionCharge;
    
    public function getInfoByDate($start,$end) {        
        $query = $this->db->query("
            SELECT 
               cacc.ID, cacc.ClientID AS comID, com.CompanyName, bacc.BankName, cacc.Reference, tran.StatusName, cacc.Date, 
               cacc.Amount, cacc.TransactionCharge, cacc.ConversionCharge 
            FROM
                clients_account as cacc
            INNER JOIN
                company_setup as com                
            ON
            	cacc.ClientID = com.ID
            INNER JOIN 
                bank_account_setup as bacc
            ON
            	cacc.BankID = bacc.ID                
            INNER JOIN
                transaction_status_setup as tran 
            ON
                cacc.StatusID = tran.ID 
            WHERE    
                cacc.Date BETWEEN '".$start."' AND '".$end."';");
         return $query->result();
    }
    public function getAllInfo() {        
        $query = $this->db->query("
            SELECT 
               cacc.ID, com.CompanyName, bacc.BankName, cacc.Reference, tran.StatusName, cacc.Date, 
               cacc.Amount, cacc.TransactionCharge, cacc.ConversionCharge 
            FROM
                clients_account as cacc
            INNER JOIN
                company_setup as com                
            ON
            	cacc.ClientID = com.ID
            INNER JOIN 
                bank_account_setup as bacc
            ON
            	cacc.BankID = bacc.ID                
            INNER JOIN
                transaction_status_setup as tran 
            ON
                cacc.StatusID = tran.ID;");
         return $query->result();
    }
    
    public function getTransactionInfoByID() {        
        $query = $this->db->query("
            SELECT 
               com.ID, com.CompanyName, bacc.BankName, cacc.Reference, tran.StatusName, cacc.Date, 
               cacc.Amount, cacc.TransactionCharge, cacc.ConversionCharge 
            FROM
                clients_account as cacc
            INNER JOIN
                company_setup as com
            INNER JOIN 
                bank_account_setup as bacc
            INNER JOIN
                transaction_status_setup as tran 
            WHERE 
                cacc.ClientID = '".$this->ID."'
            AND
                cacc.BankID = bacc.ID
            AND
                cacc.BankID = bacc.ID
            AND
                cacc.StatusID = tran.ID;");
         return $query->row();
    }

    public function insert(){
        $data = array(
            'ClientID' => $this->ClientName,
            'BankID' => $this->BankName,
            'Reference' => $this->Reference,
            'Amount' => $this->Amount,
            'StatusID' => $this->Status,
            'Date' => $this->Date,
            'TransactionCharge' => $this->TransactionCharge,
            'ConversionCharge' => $this->ConversionCharge
        );
        $this->db->insert('clients_account', $data);
    }
    
    public function edit() {
        $this->db->select('*');
        $this->db->from('clients_account');
        $this->db->where('ID', $this->ID);
        $query = $this->db->get();
        return $query->row();
    }
    
    public function update() {
        $data = array(
            'ClientID' => $this->ClientName,
            'BankID' => $this->BankName,
            'Reference' => $this->Reference,
            'Amount' => $this->Amount,
            'StatusID' => $this->Status,
            'Date' => $this->Date,
            'TransactionCharge' => $this->TransactionCharge,
            'ConversionCharge' => $this->ConversionCharge
        );
        $this->db->where('ID', $this->ID);
        $this->db->update('clients_account', $data);
    }
    
    public function deleteTransaction() {
        $this->db->where('ID', $this->ID);
        $this->db->delete('clients_account');
    }
}

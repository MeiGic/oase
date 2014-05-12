DROP DATABASE oase;

CREATE DATABASE oase DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci; 

USE oase; 

CREATE TABLE oase_unit 
  ( 
     u_id       INTEGER PRIMARY KEY, 
     u_name     VARCHAR(100) NOT NULL, 
     u_address  VARCHAR(1000) NOT NULL, 
     type       VARCHAR(1000) NOT NULL, 
     found_time DATE NOT NULL, 
     manager    INTEGER NOT NULL 
  ) 
ENGINE = INNODB 
CHARACTER SET = utf8; 

CREATE TABLE oase_employee 
  ( 
     e_id      INTEGER PRIMARY KEY auto_increment, 
     type      VARCHAR(50) NOT NULL, 
     e_name    VARCHAR(100) NOT NULL, 
     sex       VARCHAR(10), 
     birthday  DATE, 
     address   VARCHAR(1000), 
     join_time DATE, 
     salary    INTEGER, 
     u_id      INTEGER, 
     FOREIGN KEY(u_id) REFERENCES oase_unit(u_id) ON DELETE CASCADE 
  ) 
ENGINE = INNODB 
CHARACTER SET = utf8; 

CREATE TABLE oase_work_log 
  ( 
     wl_id      INTEGER PRIMARY KEY auto_increment, 
     e_id       INTEGER, 
     date       DATE, 
     start_time TIME, 
     stop_time  TIME, 
     FOREIGN KEY(e_id) REFERENCES oase_employee(e_id) ON DELETE CASCADE 
  ) 
ENGINE = INNODB 
CHARACTER SET = utf8; 

CREATE TABLE oase_payment 
  ( 
     payment_id INTEGER PRIMARY KEY auto_increment, 
     time_log   VARCHAR(10) NOT NULL, 
     e_id       INTEGER NOT NULL, 
     work_log   INTEGER NOT NULL, 
     salary     INTEGER NOT NULL, 
     FOREIGN KEY(e_id) REFERENCES oase_employee(e_id) ON DELETE CASCADE 
  ) 
ENGINE = INNODB 
CHARACTER SET = utf8; 

CREATE TABLE oase_product_cate 
  ( 
     pc_id   INTEGER auto_increment PRIMARY KEY, 
     pc_name VARCHAR(50) NOT NULL 
  ) 
ENGINE = INNODB 
CHARACTER SET = utf8; 

CREATE TABLE oase_product 
  ( 
     p_id    INTEGER PRIMARY KEY auto_increment, 
     p_name  VARCHAR(50) NOT NULL, 
     timelog TIMESTAMP, 
     size    VARCHAR(10) NOT NULL, 
     price   INTEGER NOT NULL, 
     temp    INTEGER, 
     sweet   INTEGER, 
     offer   BOOLEAN NOT NULL, 
     pc_id   INTEGER NOT NULL, 
     FOREIGN KEY(pc_id) REFERENCES oase_product_cate(pc_id) ON DELETE CASCADE 
  ) 
ENGINE = INNODB 
CHARACTER SET = utf8; 

CREATE TABLE oase_material_cate 
  ( 
     mc_id   INTEGER PRIMARY KEY auto_increment, 
     mc_name VARCHAR(100) NOT NULL 
  ) 
ENGINE = INNODB 
CHARACTER SET = utf8; 

CREATE TABLE oase_material 
  ( 
     m_id   INTEGER PRIMARY KEY auto_increment, 
     m_name VARCHAR(1000) NOT NULL, 
     mc_id  INTEGER NOT NULL, 
     FOREIGN KEY(mc_id) REFERENCES oase_material_cate(mc_id) ON DELETE CASCADE 
  ) 
ENGINE = INNODB 
CHARACTER SET = utf8; 

CREATE TABLE oase_material_state 
  ( 
     ms_id  INTEGER PRIMARY KEY auto_increment, 
     u_id   INTEGER NOT NULL, 
     m_id   INTEGER NOT NULL, 
     remain INTEGER DEFAULT '0' 
  ) 
ENGINE = INNODB 
CHARACTER SET = utf8; 

CREATE TABLE oase_material_order 
  ( 
     mol_id      INTEGER PRIMARY KEY auto_increment, 
     timelog     TIMESTAMP, 
     store_id    INTEGER NOT NULL, 
     supplier_id INTEGER NOT NULL, 
     state       VARCHAR(1000) NOT NULL, 
     stuff       VARCHAR(1000) NOT NULL, 
     FOREIGN KEY(store_id) REFERENCES oase_unit(u_id) ON DELETE CASCADE, 
     FOREIGN KEY(supplier_id) REFERENCES oase_unit(u_id) ON DELETE CASCADE 
  ) 
ENGINE = INNODB 
CHARACTER SET = utf8; 

CREATE TABLE oase_material_order_deliver 
  ( 
     mod_id        INTEGER PRIMARY KEY auto_increment, 
     mol_id        INTEGER NOT NULL, 
     deliver_state VARCHAR(1000) NOT NULL, 
     delivery_man  INTEGER NOT NULL, 
     timelog       TIMESTAMP, 
     FOREIGN KEY(mol_id) REFERENCES oase_material_order(mol_id) ON DELETE 
     CASCADE 
  ) 
ENGINE = INNODB 
CHARACTER SET = utf8; 

CREATE TABLE oase_cookbook 
  ( 
     cb_id INTEGER PRIMARY KEY auto_increment, 
     p_id  INTEGER NOT NULL, 
     m_id  INTEGER NOT NULL, 
     count INTEGER NOT NULL DEFAULT '1', 
     FOREIGN KEY(p_id) REFERENCES oase_product(p_id) ON DELETE CASCADE, 
     FOREIGN KEY(m_id) REFERENCES oase_material(m_id) ON DELETE CASCADE 
  ) 
ENGINE = INNODB 
CHARACTER SET = utf8; 

CREATE TABLE oase_special_order 
  ( 
     so_id   INTEGER PRIMARY KEY auto_increment, 
     so_name VARCHAR(1000) NOT NULL, 
     price   INTEGER NOT NULL DEFAULT '1', 
     m_id    INTEGER NOT NULL, 
     count   INTEGER NOT NULL DEFAULT '1' 
  ) 
ENGINE = INNODB 
CHARACTER SET = utf8; 

CREATE TABLE oase_custormer_style_cate 
  ( 
     csc_id   INTEGER auto_increment PRIMARY KEY, 
     csc_name VARCHAR(1000) NOT NULL 
  ) 
ENGINE = INNODB 
CHARACTER SET = utf8; 

CREATE TABLE oase_customer_style 
  ( 
     cs_id   INTEGER PRIMARY KEY auto_increment, 
     cs_name VARCHAR(1000) NOT NULL, 
     csc_id  INTEGER NOT NULL DEFAULT '1', 
     FOREIGN KEY(csc_id) REFERENCES oase_custormer_style_cate(csc_id) ON DELETE 
     CASCADE 
  ) 
ENGINE = INNODB 
CHARACTER SET = utf8; 

CREATE TABLE oase_order 
  ( 
     o_id            INTEGER PRIMARY KEY auto_increment, 
     timelog         TIMESTAMP, 
     price           INTEGER NOT NULL, 
     o_address       VARCHAR(1000), 
     deliver_request BOOLEAN DEFAULT '0', 
     state           VARCHAR(100), 
     mem_id          VARCHAR(500), 
     u_id            INTEGER NOT NULL, 
     FOREIGN KEY(u_id) REFERENCES oase_unit(u_id) ON DELETE CASCADE 
  ) 
ENGINE = INNODB 
CHARACTER SET = utf8; 

CREATE TABLE oase_order_detail 
  ( 
     od_id INTEGER PRIMARY KEY, 
     o_id  INTEGER NOT NULL, 
     p_id  INTEGER NOT NULL, 
     count INTEGER NOT NULL DEFAULT '1', 
     so_id INTEGER, 
     cs_id INTEGER, 
     FOREIGN KEY(o_id) REFERENCES oase_order(o_id) ON DELETE CASCADE, 
     FOREIGN KEY(p_id) REFERENCES oase_product(p_id) ON DELETE CASCADE, 
     FOREIGN KEY(so_id) REFERENCES oase_special_order(so_id) ON DELETE CASCADE, 
     FOREIGN KEY(cs_id) REFERENCES oase_customer_style(cs_id) ON DELETE CASCADE 
  ) 
ENGINE = INNODB 
CHARACTER SET = utf8; 

CREATE TABLE oase_order_deliver 
  ( 
     od_id          INTEGER PRIMARY KEY auto_increment, 
     o_id           INTEGER NOT NULL, 
     delivery_state VARCHAR(50) NOT NULL, 
     delivery_time  DATETIME NOT NULL, 
     delivery_man   INTEGER NOT NULL, 
     FOREIGN KEY(o_id) REFERENCES oase_order(o_id) ON DELETE CASCADE 
  ) 
ENGINE = INNODB 
CHARACTER SET = utf8; 


CREATE TABLE oase_member 
  ( 
     mem_id       INTEGER PRIMARY KEY auto_increment, 
     jointime     DATE NOT NULL, 
     mem_name     VARCHAR(1000) NOT NULL, 
     mem_address  VARCHAR (100000), 
     carrer       VARCHAR(1000), 
     mem_birthday DATE NOT NULL 
  ) 
ENGINE = INNODB 
CHARACTER SET = utf8; 

CREATE TABLE oase_web_dashboard 
  ( 
     wd_id   INTEGER PRIMARY KEY auto_increment, 
     timelog TIMESTAMP, 
     title   VARCHAR(1000) NOT NULL, 
     content VARCHAR(100000) 
  ) 
ENGINE = INNODB 
CHARACTER SET = utf8; 

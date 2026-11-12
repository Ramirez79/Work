# copy /etc/gettytab to /nsconfig, removing any line that starts with "default"
cat /etc/gettytab | grep -v ^default > /nsconfig/gettytab

# Append the new line to the file 
echo "default::cb:ce:ck:lc:fd#1000:im=\r\n$1 :sp#1200::if=/etc/issue:" >> /nsconfig/gettytab

# Copy the new file to /etc
cp /nsconfig/gettytab /etc/gettytab

# Update rc.netscaler to copy the updated getty file to /etc/ when netscaler starts
cat /nsconfig/rc.netscaler | grep -v "cp /nsconfig/gettytab /etc" > /nsconfig/rc.netscaler
echo "cp /nsconfig/gettytab /etc" >> /nsconfig/rc.netscaler
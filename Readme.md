# Extra customer fields in PrestaShop 1.7 Module

This is a module for PrestaShop, an open-source eCommerce platform, that adds extra fields to the customer registration and account pages. The module adds fields for social media account information and preferred communication method.

The module is licensed under the Academic Free License (AFL 3.0) and was developed by vallka. The module's version is 1.0.0, and it is compatible with PrestaShop 1.7 and above.

The module installs a new database table named extra_customer_fields to store the additional information. The module also registers several hooks to integrate with the customer registration and account pages. The additionalCustomerFormFields hook adds the extra fields to the customer registration form. The validateCustomerFormFields hook validates the extra fields before saving them to the database. The actionObjectCustomerUpdateAfter and actionObjectCustomerAddAfter hooks update and add the extra fields to the database when a customer is updated or added. Finally, the actionCustomerFormBuilderModifier hook modifies the customer account form to include the extra fields.

The module has two methods for reading and writing the extra field values: readModuleValues and writeModuleValues. The readModuleValues method reads the extra field values from the database for a given customer ID, and the writeModuleValues method writes the extra field values to the database for a given customer ID.

## Licensing

This source file is subject to the Academic Free License (AFL 3.0)
that is available through the world-wide-web at this URL:
http://opensource.org/licenses/afl-3.0.php
If you did not receive a copy of the license and are unable to
obtain it through the world-wide-web, please send an email
to license@prestashop.com so we can send you a copy immediately.

## DISCLAIMER
 

THIS SOFTWARE IS PROVIDED BY THE COPYRIGHT HOLDERS AND CONTRIBUTORS "AS IS" AND ANY EXPRESS OR IMPLIED WARRANTIES, INCLUDING, BUT NOT LIMITED TO, THE IMPLIED WARRANTIES OF MERCHANTABILITY AND FITNESS FOR A PARTICULAR PURPOSE ARE DISCLAIMED. IN NO EVENT SHALL THE COPYRIGHT HOLDER OR CONTRIBUTORS BE LIABLE FOR ANY DIRECT, INDIRECT, INCIDENTAL, SPECIAL, EXEMPLARY, OR CONSEQUENTIAL DAMAGES (INCLUDING, BUT NOT LIMITED TO, PROCUREMENT OF SUBSTITUTE GOODS OR SERVICES; LOSS OF USE, DATA, OR PROFITS; OR BUSINESS INTERRUPTION) HOWEVER CAUSED AND ON ANY THEORY OF LIABILITY, WHETHER IN CONTRACT, STRICT LIABILITY, OR TORT (INCLUDING NEGLIGENCE OR OTHERWISE) ARISING IN ANY WAY OUT OF THE USE OF THIS SOFTWARE, EVEN IF ADVISED OF THE POSSIBILITY OF SUCH DAMAGE.
